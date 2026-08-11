/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

(() => {
    const avatarWraper = document.querySelector('[data-area="avatar"]');
    if (!avatarWraper) {
        return;
    }
    const pickerZone = avatarWraper.querySelector('[data-area="picker"]');
    const pickerInput = pickerZone.querySelector('input[type="file"]');
    if (!pickerZone || !pickerInput) {
        return;
    }
    const opts = { ...avatarWraper.dataset }; // Copy sang object để dùng độc lập

    const avatarCropper = avatarWraper.querySelector('[data-area="cropper"]');
    const frameCropper = avatarWraper.querySelector('[data-area="frame"]');
    const actionsCropper = avatarWraper.querySelector('[data-toggle="actions"]');
    const controlsCropper = avatarWraper.querySelector('[data-toggle="controls"]');

    const btnChangeFile = avatarCropper.querySelector('[data-toggle="change-file"]');
    const btnZoomIn = avatarCropper.querySelector('[data-act="in"]');
    const btnZoomOut = avatarCropper.querySelector('[data-act="out"]');
    const btnRotateLeft = avatarCropper.querySelector('[data-act="ccw"]');
    const btnRotateRight = avatarCropper.querySelector('[data-act="cw"]');
    const btnReset = avatarCropper.querySelector('[data-act="reset"]');
    const btnSave = avatarCropper.querySelector('[data-toggle="save"]');

    // DOM của ảnh góc, thanh trượt
    let imgCropper, angleCropper;
    const sliderCropper = avatarCropper.querySelector('[data-toggle="slider"]');

    let dragDepth = 0;
    let currentUrl = null;
    let currentFile = null; // File gốc, giữ lại để gửi kèm thông số cắt

    const cropperData = {
        loaded: false, // Ảnh đã load xong chưa
        frame: 0, // Kich thước khung cropper hiện tại
        natWidth: 0, // Chiều rộng thật của ảnh
        natHeight: 0, // Chiều cao thật của ảnh
        nx: 0, // Tọa độ tâm khung cropper theo hệ toạ độ màn hình
        ny: 0,
        scale: 1, // Tỉ lệ phóng to/thu nhỏ
        deg: 0, // Góc xoay
    }

    const RAD = Math.PI / 180;
    function clamp(v, lo, hi) { return v < lo ? lo : (v > hi ? hi : v); }

    // Các opts này phải chuyển về float vì dataset trả về string
    [
        'maxHeight', 'maxScale', 'maxSize', 'maxWidth', 'minHeight', 'minWidth',
        'rotateStep', 'zoomStep', 'holdInterval', 'holdDelay'
    ].forEach(function (k) {
        opts[k] = parseFloat(opts[k]);
    });

    // Định dạng dung lượng file thành chuỗi dễ đọc
    function formatBytes(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return Math.round(bytes / 1024) + ' KB';
        return (bytes / 1024 / 1024).toFixed(1).replace(/\.0$/, '') + ' MB';
    }

    // Kiểm tra xem có file được kéo vào không
    function hasFiles(e) {
        var types = (e.dataTransfer && e.dataTransfer.types) || [];
        return Array.prototype.indexOf.call(types, 'Files') !== -1;
    }

    // Hiển thị toast lỗi
    function fail(message, ...args) {
        const msgKey = `msg${message.charAt(0).toUpperCase() + message.slice(1)}`;
        let msg = avatarWraper.dataset[msgKey] || message;

        let i = 0;
        msg = msg.replace(/%s/g, () => {
            return i < args.length ? String(args[i++]) : '%s';
        });

        return nukeviet.toast(msg, 'error');
    }

    // Đọc phần đầu file để sniff định dạng
    function readHead(file, length) {
        return new Promise(function (resolve, reject) {
            var reader = new FileReader();
            reader.onload = function () { resolve(new Uint8Array(reader.result)); };
            reader.onerror = function () { reject(reader.error); };
            reader.readAsArrayBuffer(file.slice(0, length));
        });
    }

    // Nhận dạng định dạng thật của file
    function sniffType(bytes) {
        if (bytes[0] === 0x89 && bytes[1] === 0x50 && bytes[2] === 0x4E && bytes[3] === 0x47) {
            return 'image/png';
        }
        if (bytes[0] === 0xFF && bytes[1] === 0xD8 && bytes[2] === 0xFF) {
            return 'image/jpeg';
        }
        if (ascii(bytes, 0, 4) === 'GIF8') {
            return 'image/gif';
        }
        if (ascii(bytes, 0, 4) === 'RIFF' && ascii(bytes, 8, 12) === 'WEBP') {
            return 'image/webp';
        }
        if (ascii(bytes, 4, 8) === 'ftyp') {
            var brand = ascii(bytes, 8, 12);
            if (brand === 'avif' || brand === 'avis') return 'image/avif';
            if (/^(heic|heix|hevc|hevx|mif1|msf1|heim|heis)$/.test(brand)) return 'image/heic';
        }
        return null;
    }

    // Giải phóng object URL của lượt chọn trước
    function releaseCurrent() {
        if (currentUrl) {
            URL.revokeObjectURL(currentUrl);
            currentUrl = null;
        }
    }

    // Đọc chuỗi ASCII từ mảng byte
    function ascii(bytes, from, to) {
        var s = '';
        for (var i = from; i < to && i < bytes.length; i++) s += String.fromCharCode(bytes[i]);
        return s;
    }

    // Nạp ảnh để lấy kích thước thật và xác nhận trình duyệt giải mã được
    function probeImage(url) {
        return new Promise(function (resolve, reject) {
            var img = new Image();
            img.onload = function () {
                // naturalWidth = 0 nghĩa là giải mã hỏng (Safari đôi khi vẫn bắn onload)
                if (!img.naturalWidth || !img.naturalHeight) {
                    reject(new Error('decode'));
                    return;
                }
                resolve({ width: img.naturalWidth, height: img.naturalHeight });
            };
            img.onerror = function () { reject(new Error('decode')); };
            img.src = url;
        });
    }

    function handleFiles(fileList) {
        // Không có ảnh nào được chọn hoặc quá 1 ảnh
        if (!fileList || fileList.length === 0) return fail('noFile');
        if (fileList.length > 1) return fail('tooMany');

        var file = fileList[0];

        // Kích thước rỗng hoặc quá lớn
        if (file.size === 0) return fail('empty');
        if (file.size > opts.maxSize) {
            return fail('tooBig', formatBytes(file.size), formatBytes(opts.maxSize));
        }

        pickerZone.classList.add('is-busy');

        // Đọc phần đầu file để xác định định dạng
        readHead(file, 16)
            .then(function (bytes) {
                var real = sniffType(bytes);

                if (real === 'image/heic') throw { code: 'heic' };
                if (!real || ['image/png', 'image/jpeg'].indexOf(real) === -1) throw { code: 'badType' };

                releaseCurrent();
                currentUrl = URL.createObjectURL(file);

                return probeImage(currentUrl).then(function (size) {
                    // Kích thước quá lớn
                    if (size.width > opts.maxWidth || size.height > opts.maxHeight) {
                        throw { code: 'maxPixels' };
                    }
                    // Kích thước quá nhỏ
                    if (size.width < opts.minWidth || size.height < opts.minHeight) {
                        throw { code: 'minPixels' };
                    }
                    return { file: file, type: real, url: currentUrl, width: size.width, height: size.height };
                });
            })
            .then(function (result) {
                pickerZone.classList.remove('is-busy');
                initCropper(result);
            })
            .catch(function (err) {
                console.error(err);
                pickerZone.classList.remove('is-busy');
                releaseCurrent();
                fail((err && err.code) || 'Decode image failed!');
            });
    }

    // Tính toán kích thước khung cropper của ảnh
    function cropperFrameSizeMeasure() {
        const width = frameCropper.clientWidth;
        if (width && width !== cropperData.frame) {
            cropperData.frame = width;
            cropperRender();
        }
    }

    // Vẽ lại style của ảnh và hiển thị góc xoay
    function cropperRender() {
        if (!cropperData.loaded) return;
        imgCropper.style.transform =
            'translate(' + (cropperData.nx * cropperData.frame) + 'px,' + (cropperData.ny * cropperData.frame) + 'px) ' +
            'rotate(' + cropperData.deg + 'deg) ' +
            'scale(' + (coverScale() * cropperData.scale) + ')';
        angleCropper.textContent = Math.round(cropperData.deg) + '°';
        angleCropper.classList.toggle('is-on', Math.round(cropperData.deg) !== 0);
    }

    // Lấy data cropper hiện tại để gửi lên server
    function getCropData() {
        if (!cropperData.loaded) return null;
        var t = cropperData.deg * RAD;
        var ac = Math.abs(Math.cos(t)), as = Math.abs(Math.sin(t));
        var rw = cropperData.natWidth * ac + cropperData.natHeight * as;
        var rh = cropperData.natWidth * as + cropperData.natHeight * ac;
        var side = minSide() / (kFactor() * cropperData.scale);   // cạnh khung trong hệ ảnh xoay

        return {
            rotate: +cropperData.deg.toFixed(2),
            sx: Math.round(rw / 2 - cropperData.nx * side - side / 2),
            sy: Math.round(rh / 2 - cropperData.ny * side - side / 2),
            sw: Math.round(side),
            sh: Math.round(side),
            rotatedWidth: Math.round(rw),
            rotatedHeight: Math.round(rh),
            sourceWidth: cropperData.natWidth,
            sourceHeight: cropperData.natHeight,
            scale: +cropperData.scale.toFixed(3)
        };
    }

    // Cập nhật các thông số cropper và vẽ lại. Gọi mỗi khi thay đổi vị trí, tỉ lệ, góc xoay, resize khung cropper.
    function cropperApply() {
        clampPan();
        cropperRender();
        if (sliderCropper.value !== String(cropperData.scale)) sliderCropper.value = cropperData.scale;
    }

    /**
     * Kẹp vị trí. Ràng buộc nằm trong hệ toạ độ của ảnh, không phải màn hình,
     * nên phải quay ngược vector lệch về hệ ảnh, kẹp theo hộp, rồi quay lại.
     * Kẹp thẳng trên màn hình sẽ cho phép hở góc khi ảnh nghiêng.
     */
    function clampPan() {
        if (!cropperData.loaded) return;
        var side = minSide() / cropperData.scale;  // cạnh khung quy về px ảnh
        var m = side / kFactor();                  // hệ số đổi nx,ny sang px ảnh
        var t = cropperData.deg * RAD;
        var c = Math.cos(t), s = Math.sin(t);

        // Vị trí tâm khung trong hệ ảnh
        var ux = -m * (c * cropperData.nx + s * cropperData.ny);
        var uy = -m * (-s * cropperData.nx + c * cropperData.ny);

        var limX = Math.max(0, (cropperData.natWidth - side) / 2);
        var limY = Math.max(0, (cropperData.natHeight - side) / 2);
        ux = clamp(ux, -limX, limX);
        uy = clamp(uy, -limY, limY);

        // Quay trở lại hệ màn hình
        cropperData.nx = -(c * ux - s * uy) / m;
        cropperData.ny = -(s * ux + c * uy) / m;
    }

    function minSide() { return Math.min(cropperData.natWidth, cropperData.natHeight); }
    // Tỉ lệ để ảnh vừa đủ phủ kín khung ở góc hiện tại
    function coverScale() {
        return cropperData.frame * kFactor() / minSide();
    }
    // Đặt tỉ lệ phóng to/thu nhỏ
    function setScale(next) { cropperData.scale = clamp(next, 1, opts.maxScale); cropperApply(); }
    // Xoay
    function rotateBy(delta) {
        cropperData.deg = ((cropperData.deg + delta) % 360 + 360) % 360;
        // giữ trong [-180, 180) cho dễ đọc
        if (cropperData.deg > 180) cropperData.deg -= 360;
        cropperApply();
    }
    // Đặt góc xoay
    function setAngle(deg) { cropperData.deg = deg; cropperApply(); }
    // Đặt lại các thông số về mặc định
    function reset() { cropperData.nx = 0; cropperData.ny = 0; cropperData.scale = 1; cropperData.deg = 0; cropperApply(); }

    // Hệ số nở của hộp bao khi khung vuông nghiêng góc θ
    function kFactor() {
        var t = cropperData.deg * RAD;
        return Math.abs(Math.cos(t)) + Math.abs(Math.sin(t));
    }

    // Xử lý cropper
    function initCropper(result) {
        if (!avatarCropper) return;
        pickerZone.classList.add('hidden', 'hide', 'd-none');

        let html = `
            <img src="${result.url}" alt="Avatar">
            <div class="frame-mask"></div>
            <div class="frame-ring"></div>
            <div class="frame-angle" data-toggle="angle">0°</div>
        `;
        frameCropper.innerHTML = html;
        avatarCropper.classList.remove('hidden', 'hide', 'd-none');

        imgCropper = frameCropper.querySelector('img');
        angleCropper = frameCropper.querySelector('[data-toggle="angle"]');

        window.dispatchEvent(new Event('resize')); // Để cropper resize lại

        // Reset lại style
        imgCropper.removeAttribute('style');

        cropperData.loaded = false;
        cropperData.natWidth = result.width;
        cropperData.natHeight = result.height;
        currentFile = result.file;

        // Tính toán lại các thông số khi trình duyệt đã giải mã xong ảnh
        function loadDone() {
            cropperData.natWidth = imgCropper.naturalWidth || cropperData.natWidth;
            cropperData.natHeight = imgCropper.naturalHeight || cropperData.natHeight;

            imgCropper.style.width = cropperData.natWidth + 'px';
            imgCropper.style.height = cropperData.natHeight + 'px';
            imgCropper.style.marginLeft = (-cropperData.natWidth / 2) + 'px';
            imgCropper.style.marginTop = (-cropperData.natHeight / 2) + 'px';

            cropperData.loaded = true;

            cropperFrameSizeMeasure();
            cropperApply();
        }

        if (imgCropper.complete && imgCropper.naturalWidth) {
            loadDone();
        } else {
            imgCropper.addEventListener('load', loadDone);
            imgCropper.addEventListener('error', function () {
                fail('decode');
            });
        }
    }

    // Sự kiện chọn file
    pickerInput.addEventListener('change', () => {
        handleFiles(pickerInput.files);

        // Reset để chọn lại đúng file vừa rồi vẫn bắn 'change'
        pickerInput.value = '';
    });

    // Sự kiện bấm vào vùng chọn file
    pickerZone.addEventListener('click', () => {
        pickerInput.click();
    });

    // Sự kiện bàn phím
    pickerZone.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ' || e.key === 'Spacebar') {
            e.preventDefault();
            pickerInput.click();
        }
    });

    // Sự kiện kéo thả
    pickerZone.addEventListener('dragenter', function (e) {
        if (!hasFiles(e)) return;
        e.preventDefault();
        dragDepth++;
        pickerZone.classList.add('is-dragging');
    });
    pickerZone.addEventListener('dragover', function (e) {
        if (!hasFiles(e)) return;
        e.preventDefault();
        // Bắt buộc: nếu không set, Firefox hiện con trỏ "cấm"
        e.dataTransfer.dropEffect = 'copy';
    });

    /**
     * dragenter/dragleave bắn cả khi con trỏ đi qua phần tử con
     * nên phải đếm độ sâu thay vì bật/tắt trực tiếp.
     */
    pickerZone.addEventListener('dragleave', function (e) {
        if (!hasFiles(e)) return;
        dragDepth = Math.max(0, dragDepth - 1);
        if (dragDepth === 0) pickerZone.classList.remove('is-dragging');
    });

    pickerZone.addEventListener('drop', function (e) {
        if (!hasFiles(e)) return;
        e.preventDefault();
        e.stopPropagation();
        dragDepth = 0;
        pickerZone.classList.remove('is-dragging');
        handleFiles(e.dataTransfer.files);
    });

    // Ấn nút đổi để chọn ảnh khác
    btnChangeFile.addEventListener('click', (e) => {
        e.preventDefault();

        reset();

        pickerZone.classList.remove('hidden', 'hide', 'd-none');
        avatarCropper.classList.add('hidden', 'hide', 'd-none');
        frameCropper.innerHTML = '';
        releaseCurrent();
        window.dispatchEvent(new Event('resize')); // Để cropper resize lại
        imgCropper = null;
        currentFile = null;
    });

    /**
     * Gửi ảnh gốc kèm thông số cắt lên máy chủ.
     * Gửi ảnh gốc chứ không phải ảnh đã vẽ lại ở client để máy chủ tự cắt,
     * tránh phụ thuộc vào canvas và giữ nguyên chất lượng ảnh nguồn.
     */
    btnSave.addEventListener('click', function (e) {
        e.preventDefault();
        if (btnSave.disabled) return;

        const crop = getCropData();
        if (!crop || !currentFile) return fail('noFile');

        const body = new FormData();
        body.append('image_file', currentFile, currentFile.name);
        body.append('checkss', opts.checkss);
        body.append('crop_x', crop.sx);
        body.append('crop_y', crop.sy);
        body.append('crop_width', crop.sw);
        body.append('crop_height', crop.sh);
        body.append('crop_rotate', crop.rotate);
        body.append('rotated_width', crop.rotatedWidth);
        body.append('rotated_height', crop.rotatedHeight);
        body.append('source_width', crop.sourceWidth);
        body.append('source_height', crop.sourceHeight);

        btnSave.disabled = true;
        avatarCropper.classList.add('is-busy');

        fetch(opts.uploadUrl, {
            method: 'POST',
            body: body,
            credentials: 'same-origin'
        })
            .then(function (res) { return res.json(); })
            .then(function (res) {
                if (!res || res.status !== 'ok') {
                    throw { mess: res.mess || 'save' };
                }
                // Báo cho trang cha cập nhật ảnh và đóng khung
                window.parent.postMessage({
                    type: 'nv.avatar.done',
                    src: res.src,
                    name: res.filename,
                    redirect: res.redirect
                }, window.location.origin);
            })
            .catch(function (err) {
                console.error(err);
                btnSave.disabled = false;
                avatarCropper.classList.remove('is-busy');
                nukeviet.toast((err && err.mess) || 'save', 'error');
            });
    });

    /**
     * Các thông số và init sự kiện riêng của cropper
     */
    let pointers = new Map();
    let holdTimer = null, holdRepeat = null, pinch = null;

    function mid() {
        var x = 0, y = 0, n = 0;
        pointers.forEach(function (p) { x += p.x; y += p.y; n++; });
        return { x: x / n, y: y / n };
    }
    function dist() {
        var a = []; pointers.forEach(function (p) { a.push(p); });
        var dx = a[0].x - a[1].x, dy = a[0].y - a[1].y;
        return Math.sqrt(dx * dx + dy * dy);
    }

    /**
     * Xử lý kéo thả ảnh trong khung, chụm (touch)
     */
    frameCropper.addEventListener('pointerdown', function (e) {
        if (!cropperData.loaded) return;
        frameCropper.setPointerCapture(e.pointerId);
        pointers.set(e.pointerId, { x: e.clientX, y: e.clientY });
        avatarCropper.classList.add('is-dragging');
        if (pointers.size === 2) {
            pinch = { d: dist(), scale: cropperData.scale, mid: mid(), nx: cropperData.nx, ny: cropperData.ny };
        }
    });
    frameCropper.addEventListener('pointermove', function (e) {
        if (!cropperData.loaded || !pointers.has(e.pointerId)) return;
        var prev = pointers.get(e.pointerId);
        pointers.set(e.pointerId, { x: e.clientX, y: e.clientY });

        if (pointers.size === 2 && pinch) {
            cropperData.scale = clamp(pinch.scale * (dist() / pinch.d), 1, opts.maxScale);
            var m2 = mid();
            cropperData.nx = pinch.nx + (m2.x - pinch.mid.x) / cropperData.frame;
            cropperData.ny = pinch.ny + (m2.y - pinch.mid.y) / cropperData.frame;
            cropperApply();
            return;
        }
        cropperData.nx += (e.clientX - prev.x) / cropperData.frame;
        cropperData.ny += (e.clientY - prev.y) / cropperData.frame;
        clampPan();
        cropperRender();
    });
    function endPointer(e) {
        if (!pointers.has(e.pointerId)) return;
        pointers.delete(e.pointerId);
        if (pointers.size < 2) pinch = null;
        if (pointers.size === 0) {
            avatarCropper.classList.remove('is-dragging');
            cropperApply();
        }
    }
    frameCropper.addEventListener('pointerup', endPointer);
    frameCropper.addEventListener('pointercancel', endPointer);

    // Lăn chuột
    frameCropper.addEventListener('wheel', function (e) {
        if (!cropperData.loaded) return;
        e.preventDefault();
        var d = e.deltaMode === 1 ? e.deltaY * 16 : e.deltaY;
        setScale(cropperData.scale * (d > 0 ? 0.94 : 1.06));
    }, { passive: false });

    // Loạt thao tác bàn phím trên cropper
    frameCropper.addEventListener('keydown', function (e) {
        if (!cropperData.loaded) return;
        var step = e.shiftKey ? 0.08 : 0.02;
        switch (e.key) {
            case 'ArrowLeft': cropperData.nx -= step; break;
            case 'ArrowRight': cropperData.nx += step; break;
            case 'ArrowUp': cropperData.ny -= step; break;
            case 'ArrowDown': cropperData.ny += step; break;
            case '+': case '=': setScale(cropperData.scale + opts.zoomStep); return;
            case '-': case '_': setScale(cropperData.scale - opts.zoomStep); return;
            case '[': rotateBy(-opts.rotateStep); return;
            case ']': rotateBy(opts.rotateStep); return;
            default: return;
        }
        e.preventDefault();
        cropperApply();
    });

    // Hàm chạy trạng thái lặp lại khi giữ
    function runAction(act) {
        switch (act) {
            case 'in': setScale(cropperData.scale + opts.zoomStep); break;
            case 'out': setScale(cropperData.scale - opts.zoomStep); break;
            case 'ccw': rotateBy(-opts.rotateStep); break;
            case 'cw': rotateBy(opts.rotateStep); break;
            case 'reset': reset(); break;
        }
    }

    // Dừng trạng thái lặp lại khi giữ
    function stopHold() {
        clearTimeout(holdTimer); clearInterval(holdRepeat);
        holdTimer = holdRepeat = null;
    }

    function onPointerDownBtn(e) {
        var btn = e.target.closest('[data-hold]');
        if (!btn) return;
        // Chỉ nút chính của chuột; ngón tay và bút vẫn nhận bình thường
        if (e.pointerType === 'mouse' && e.button !== 0) return;
        e.preventDefault();

        var act = btn.getAttribute('data-act');
        runAction(act);                       // nhịp đầu tiên tức thì
        btn.classList.add('is-held');

        holdTimer = setTimeout(function () {
            holdRepeat = setInterval(function () { runAction(act); }, opts.holdInterval);
        }, opts.holdDelay);

        var release = function () {
            stopHold();
            btn.classList.remove('is-held');
            window.removeEventListener('pointerup', release);
            window.removeEventListener('pointercancel', release);
        };
        // Gắn ở window: nếu người dùng kéo ra ngoài nút rồi mới thả,
        // sự kiện pointerup không đến nút và nó sẽ quay mãi không dừng.
        window.addEventListener('pointerup', release);
        window.addEventListener('pointercancel', release);
    }

    function onClick(e) {
        var btn = e.target.closest('[data-act]');
        if (!btn) return;
        // Nút ấn-giữ đã chạy ở pointerdown, bỏ qua click do chuột sinh thêm.
        // Bàn phím vẫn sinh click mà không có pointerdown, nên vẫn dùng được.
        if (btn.hasAttribute('data-hold') && e.detail !== 0) return;
        runAction(btn.getAttribute('data-act'));
    }

    // Gắn sự kiện cho các nút và thanh trượt
    [controlsCropper, actionsCropper].forEach(function (c) {
        c.addEventListener('pointerdown', onPointerDownBtn);
        c.addEventListener('click', onClick);
        c.addEventListener('contextmenu', function (e) {
            if (e.target.closest('[data-hold]')) e.preventDefault();
        });
    });

    // Thay đổi thanh trượt
    sliderCropper.addEventListener('input', function () { setScale(parseFloat(sliderCropper.value)); });

    // Theo dõi resize khung cropper để tính lại kích thước khung
    let ro = null;
    if (window.ResizeObserver) {
        ro = new ResizeObserver(cropperFrameSizeMeasure);
        ro.observe(frameCropper);
    } else {
        window.addEventListener('resize', cropperFrameSizeMeasure);
    }

    // Gửi postMessage sang cha để thay đổi chiều cao của khung iframe
    let lastHeight = 0;

    function setContainerHeight() {
        const height = nukeviet.getExactContentHeight();
        if (height === lastHeight) {
            return;
        }

        lastHeight = height;
        window.parent.postMessage({
            type: 'nv.avatar.setHeight',
            height: height
        }, window.location.origin);
    }

    window.addEventListener('load', setContainerHeight);
    window.addEventListener('resize', setContainerHeight);
})();
