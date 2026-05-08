<?php

/**
 * Seeder — LoremNewsGenerator
 *
 * Sinh hometext (1-2 câu) + bodytext (3-5 đoạn) tiếng Việt mẫu cho bài seed.
 * Không cần lib ngoài. Dùng pool đoạn template TV + biến lookup theo cat.
 *
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 */

declare(strict_types=1);

namespace Seeder;

class LoremNewsGenerator
{
    /** @var array<string, array{intro:string[], body:string[]}> Template TV theo cat alias */
    private const TEMPLATES = [
        'thoi-su' => [
            'intro' => [
                'Theo nguồn tin chính thức từ cơ quan chức năng',
                'Sự việc đang thu hút sự quan tâm lớn của dư luận',
                'Diễn biến mới nhất cho thấy tình hình đang phức tạp',
            ],
            'body' => [
                'Sáng nay, đại diện cơ quan có thẩm quyền đã có buổi trao đổi với báo chí về vấn đề này. Theo đó, các phương án xử lý đã được lên kế hoạch chi tiết, đảm bảo quyền lợi cho người dân.',
                'Cơ quan điều tra đang khẩn trương làm rõ các tình tiết liên quan, đồng thời phối hợp với địa phương để hỗ trợ người dân ổn định cuộc sống.',
                'Kết quả khảo sát ban đầu cho thấy nhiều vấn đề cần được giải quyết kịp thời. Lãnh đạo địa phương đã chỉ đạo các sở ngành vào cuộc.',
                'Trong thời gian tới, các cơ quan chức năng sẽ tiếp tục theo dõi, cập nhật và công bố thông tin tới người dân theo đúng quy định.',
            ],
        ],
        'kinh-doanh' => [
            'intro' => [
                'Thị trường ghi nhận diễn biến đáng chú ý trong phiên giao dịch hôm nay',
                'Doanh nghiệp công bố kết quả kinh doanh tích cực',
                'Xu hướng đầu tư mới đang dần được hình thành',
            ],
            'body' => [
                'Báo cáo tài chính quý vừa qua cho thấy doanh thu tăng trưởng so với cùng kỳ. Lợi nhuận sau thuế cũng đạt mức cao nhất trong nhiều năm.',
                'Theo các chuyên gia phân tích, dòng tiền đang có xu hướng quay trở lại các nhóm ngành cơ bản. Nhà đầu tư cần thận trọng với các cổ phiếu có thanh khoản thấp.',
                'Doanh nghiệp đặt mục tiêu mở rộng thị trường ra khu vực Đông Nam Á trong năm tới, với kế hoạch đầu tư dây chuyền sản xuất hiện đại.',
                'Cơ quan quản lý đang xem xét các đề xuất mới nhằm tạo thuận lợi cho hoạt động kinh doanh, đồng thời đảm bảo tuân thủ quy định pháp luật.',
            ],
        ],
        'the-thao' => [
            'intro' => [
                'Trận đấu tâm điểm đêm qua đã có kết quả',
                'Đội tuyển Việt Nam tiếp tục có chuỗi trận ấn tượng',
                'Giải đấu đang đi vào giai đoạn quyết định',
            ],
            'body' => [
                'Hai đội đã cống hiến cho khán giả 90 phút bóng đá hấp dẫn với nhiều cơ hội ăn bàn. Bàn thắng quyết định được ghi ở phút 87.',
                'Huấn luyện viên trưởng đánh giá cao tinh thần thi đấu của các học trò. Ông cho biết đội bóng đã có sự chuẩn bị kỹ lưỡng cho từng đối thủ.',
                'Thống kê cho thấy đội nhà kiểm soát bóng 60% thời gian, dứt điểm 18 lần và có 7 pha bóng trúng đích.',
                'Trận đấu tiếp theo sẽ diễn ra vào cuối tuần. Người hâm mộ kỳ vọng đội bóng sẽ tiếp tục giành chiến thắng để nuôi hy vọng vào chung kết.',
            ],
        ],
        'giai-tri' => [
            'intro' => [
                'Sản phẩm âm nhạc mới ra mắt nhận được phản hồi tích cực',
                'Sự kiện văn hóa thu hút đông đảo người tham dự',
                'Dự án phim mới chính thức công bố',
            ],
            'body' => [
                'MV mới ra mắt đã nhanh chóng đạt top thịnh hành trên các nền tảng. Nhiều khán giả đánh giá đây là sản phẩm đột phá so với các bản trước.',
                'Buổi ra mắt diễn ra trong không khí trang trọng với sự tham dự của nhiều nghệ sĩ. Ekip sản xuất tiết lộ quá trình thực hiện kéo dài hơn 6 tháng.',
                'Doanh thu phòng vé cuối tuần qua chứng kiến sự bứt phá của tác phẩm trong nước. Đây là tín hiệu đáng mừng cho điện ảnh Việt.',
                'Nghệ sĩ chia sẻ rằng dự án này là tâm huyết suốt 2 năm. Sản phẩm hứa hẹn mang lại trải nghiệm khác biệt cho khán giả.',
            ],
        ],
    ];

    /** @var string[] Pool đoạn body fallback cho cat không có template riêng */
    private const GENERIC_BODY = [
        'Thông tin chi tiết về sự việc đang được các cơ quan chức năng cập nhật và công bố. Nội dung bài viết phục vụ mục đích minh hoạ giao diện theme NewsViet.',
        'Đây là dữ liệu mẫu được sinh tự động bởi NewsViet Seeder để phục vụ test layout. Trong môi trường thực tế, biên tập viên sẽ thay thế bằng nội dung thật.',
        'Các chuyên gia trong lĩnh vực đã đưa ra nhiều ý kiến đa chiều. Người dân quan tâm có thể theo dõi diễn biến tiếp theo trên các phương tiện truyền thông.',
        'Để có thông tin chi tiết hơn về chủ đề này, độc giả có thể tham khảo các bài viết liên quan trong cùng chuyên mục. Bài viết sẽ được cập nhật khi có thông tin mới.',
        'Thực tiễn cho thấy đây là một vấn đề có nhiều khía cạnh cần được phân tích kỹ lưỡng. Các bên liên quan đã có những phát biểu chính thức về quan điểm của mình.',
    ];

    private const GENERIC_INTRO = [
        'Đây là tóm tắt ngắn gọn về nội dung bài viết',
        'Bài viết cung cấp thông tin cập nhật về chủ đề',
        'Nội dung dưới đây mô tả các diễn biến mới nhất',
        'Thông tin được tổng hợp từ nhiều nguồn đáng tin cậy',
    ];

    /**
     * Sinh hometext (intro 1-2 câu).
     *
     * @param string $title       Tiêu đề bài (dùng làm seed reproducible)
     * @param string $catalias    Alias chuyên mục (cho template phù hợp)
     */
    public static function generateHometext(string $title, string $catalias): string
    {
        $seed = crc32($title . '|' . $catalias);
        mt_srand($seed);

        $pool = self::TEMPLATES[$catalias]['intro'] ?? self::GENERIC_INTRO;
        $intro = $pool[mt_rand(0, count($pool) - 1)];

        mt_srand(); // reset random
        return $intro . '. ' . $title . '.';
    }

    /**
     * Sinh bodytext (3-5 đoạn HTML).
     *
     * @param string $title
     * @param string $catalias
     */
    public static function generateBodytext(string $title, string $catalias): string
    {
        $seed = crc32($title . '|' . $catalias . '|body');
        mt_srand($seed);

        $pool = self::TEMPLATES[$catalias]['body'] ?? self::GENERIC_BODY;
        $count = mt_rand(3, 5);

        $paragraphs = [];
        $paragraphs[] = '<p><strong>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</strong></p>';

        for ($i = 0; $i < $count; $i++) {
            $idx = mt_rand(0, count($pool) - 1);
            $paragraphs[] = '<p>' . htmlspecialchars($pool[$idx], ENT_QUOTES, 'UTF-8') . '</p>';
        }

        // Đoạn cuối note đây là demo
        $paragraphs[] = '<p><em>(Bài viết mẫu được sinh bởi NewsViet Seeder phục vụ test giao diện. Liên hệ admin để cập nhật nội dung thật.)</em></p>';

        mt_srand();
        return implode("\n", $paragraphs);
    }
}
