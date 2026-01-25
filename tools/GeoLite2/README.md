# Công cụ tạo thư viện IPv4 và IPv6 cho NukeViet CMS từ CSDL của 

> Đây là cách chia theo dải IP truyền thống. Dễ hiểu

CSDL GeoLite2 tải từ https://www.maxmind.com/, giải nén GeoLite2-Country-CSV_*.zip lấy ba tệp

- GeoLite2-Country-Blocks-IPv4.csv
- GeoLite2-Country-Blocks-IPv6.csv
- GeoLite2-Country-Locations-en.csv

Bỏ vào thư mục libs. Xóa thư mục release nếu muốn tạo lại dữ liệu và chạy:

`php ipv4.php` => Tạo thư viện IPv4.

`php ipv6.php` => Tạo thư viện IPv6.

----------------------------------------------
Database and Contents Copyright (c) 2024 MaxMind, Inc.


----------------------------------------------

Use of this MaxMind product is governed by MaxMind's GeoLite2 End User License Agreement, which can be viewed at https://www.maxmind.com/en/geolite2/eula.

This database incorporates GeoNames [https://www.geonames.org] geographical data, which is made available under the Creative Commons Attribution 4.0 License. To view a copy of this license, visit https://creativecommons.org/licenses/by/4.0/.
