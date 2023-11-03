<h1> API Quản Lý File với Laravel </h1>

<h3>Mô tả </h3>
Dự án này là một RESTful API được xây dựng bằng Laravel để quản lý các hoạt động liên quan đến tệp (file) như tải lên, tải xuống và chia sẻ tệp. API cung cấp các tính năng cơ bản để quản lý tệp

Tính Năng Chính
Tải Lên Tệp (Upload File): Cho phép người dùng tải lên tệp lên hệ thống.

Tải Xuống Tệp (Download File): Cho phép người dùng tải xuống các tệp từ hệ thống.

Chia Sẻ Tệp (Share File): Cho phép người dùng tạo liên kết chia sẻ cho các tệp đã tải lên.

Xem Thông Tin Tệp (File Information): Cung cấp thông tin chi tiết về tệp như kích thước, ngày tạo, ngày cập nhật, và nhiều thông tin khác.

Xóa Tệp (Delete File): Cho phép người dùng xóa tệp khỏi hệ thống.

<h3>Bắt Đầu </h3>

Để bắt đầu sử dụng API này, bạn cần:

-   Cài Đặt Laravel
-   Cài Đặt Dự Án: git clone https://github.com/HoangThao18/demo-mediafire
-   chạy composer install để cài đặt các phụ thuộc.
-   import database: https://github.com/HoangThao18/demo-mediafire/blob/main/file_manager.sql
-   Cấu Hình Môi Trường: Cấu hình tệp .env để liên kết cơ sở dữ liệu và các thiết lập khác.
-   Chạy lệnh php artisan migrate để tạo cơ sở dữ liệu cho ứng dụng

<h3>Sử dụng </h3>
API này sử dụng các endpoint sau:

POST /api/login: đăng nhập

POST /api/register: đăng ký

POST /api/forgot-password: quên mật khẩu

GET /api/user/profile: lấy thông tin user

GET /api/user/logout: đăng xuất

GET /api/user/myfile: lấy tất cả file và folder cha

POST /api/user/change-password: đổi password (tham số truyền vào: email)

POST /api/user/folder: tạo folder

DELETE /api/user/folder: xóa folder (tham số truyền vào: mảng ids[])

put /api/user/folder/{folder}: sửa folder với id tương ứng

POST /api/user/folder/upload: upload folder (tham số truyền vào: relatives_path[], files[])

POST /api/user/folder/share: chia sẻ folder

GET /api/user/folder/{folder}: lấy folder với id tương ứng

POST /api/user/file/upload: upload file (tham số truyền vào: files[])

PUT /api/user/file/{file}: sửa file với id tương ứng

DELETE /api/user/file: xóa file

DELETE /api/download: download file (tham số truyền vào: fileIds[], folderIds[], parent_id(optional) )

DELETE /api/delete-file/{file_id}: Xóa tệp với ID tương ứng.
Chạy Ứng Dụng: Chạy php artisan serve để khởi chạy ứng dụng và sử dụng các tài khoản mặc định.
