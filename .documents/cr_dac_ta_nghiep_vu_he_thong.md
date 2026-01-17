📘 ĐẶC TẢ NGHIỆP VỤ
HỆ THỐNG SAAS QUẢN LÝ DẠY THÊM
Phiên bản chuẩn triển khai thực tế
________________________________________
I. TỔNG QUAN HỆ THỐNG
1. Mục tiêu hệ thống
Hệ thống là nền tảng SaaS (Software as a Service) phục vụ quản lý hoạt động dạy thêm – học thêm cho:
•	Giáo viên cá nhân
•	Trung tâm dạy thêm quy mô nhỏ – vừa
Hệ thống hỗ trợ đa hình thức học tập:
•	🏫 Dạy Offline
•	💻 Dạy Online (Zoom / Google Meet)
•	🎥 Học qua Video tự học (LMS nhẹ)
🎯 Mục tiêu cốt lõi:
•	Số hóa toàn bộ nghiệp vụ dạy thêm
•	Giảm phụ thuộc sổ sách – Excel
•	Dễ dùng với giáo viên Việt Nam
•	Mở rộng linh hoạt theo gói thuê bao
________________________________________
2. Mô hình vận hành tổng thể
Thành phần	Vai trò
Admin (System Owner)	Đơn vị cung cấp nền tảng SaaS
Teacher (Tenant)	Khách hàng thuê hệ thống
Student	Người dùng học tập, phụ thuộc Teacher
•	Mỗi Teacher là một Tenant độc lập
•	Dữ liệu cách ly hoàn toàn giữa các Teacher
•	Mô hình Subscription theo năm
________________________________________
II. MÔ HÌNH ROLE & PHÂN QUYỀN
1. Role: ADMIN (Super Admin)
1.1 Định nghĩa
Admin là chủ sở hữu và vận hành hệ thống, không tham gia hoạt động giảng dạy.
1.2 Quyền nghiệp vụ
Admin có toàn quyền:
•	Quản lý Pricing Plan
•	Quản lý tài khoản Teacher
•	Quản lý Subscription & trạng thái thuê bao
•	Theo dõi thanh toán – doanh thu
•	Cấu hình hệ thống:
o	Giới hạn theo gói
o	Bật / tắt module
o	Cấu hình thông báo, email
📌 Admin KHÔNG truy cập dữ liệu chi tiết lớp học của Teacher.
________________________________________
2. Role: TEACHER (Giáo viên – Tenant)
2.1 Định nghĩa
Teacher là khách hàng mua gói dịch vụ để sử dụng hệ thống quản lý lớp học riêng.
2.2 Đặc điểm
•	Có workspace riêng
•	Không truy cập dữ liệu Teacher khác
•	Quản lý toàn bộ:
o	Lớp học
o	Học sinh
o	Buổi học
o	Điểm danh
o	Học phí
o	Báo cáo
________________________________________
3. Role: STUDENT (Học sinh)
3.1 Định nghĩa
Student là tài khoản học tập do Teacher tạo.
3.2 Quy tắc
•	❌ Không tự đăng ký
•	✅ Thuộc duy nhất 1 Teacher
•	Quyền hạn chỉ phục vụ học tập
________________________________________
III. SUBSCRIPTION & TRẠNG THÁI TÀI KHOẢN
1. Pricing Plan (Gói dịch vụ)
Mỗi gói quy định:
•	Số lượng học sinh tối đa
•	Số lượng lớp tối đa
•	Module được phép sử dụng
•	Dung lượng lưu trữ
•	Tính năng nâng cao (QR, LMS, báo cáo…)
________________________________________
2. Trạng thái Subscription
Trạng thái	Mô tả
Active	Sử dụng đầy đủ
Grace Period	Hết hạn – chỉ xem dữ liệu
Suspended	Khóa hoàn toàn
________________________________________
3. Quy tắc vận hành
3.1 Trước khi hết hạn
•	Trước 7 ngày:
o	Thông báo trên Dashboard
o	Gửi Email
3.2 Grace Period
Teacher:
•	✅ Đăng nhập
•	✅ Xem dữ liệu
•	❌ Không được:
o	Tạo lớp
o	Điểm danh
o	Đăng bài
o	Chấm điểm
o	Gửi thông báo
3.3 Suspended
•	Teacher ❌ đăng nhập
•	Student ❌ đăng nhập
•	Hiển thị thông báo hệ thống
________________________________________
IV. NGHIỆP VỤ CHI TIẾT ROLE TEACHER
________________________________________
1. Quản lý lớp học (Class Management)
1.1 Tạo lớp học
Thông tin bắt buộc:
•	Tên lớp
•	Môn học
•	Hình thức:
o	Offline
o	Online
o	Hybrid
•	Lịch học:
o	Cố định
o	Linh hoạt
•	Giá học phí
________________________________________
1.2 Quản lý phòng học Offline
•	Khai báo phòng:
o	Tên phòng
o	Sức chứa
•	Quy tắc:
o	Không cho phép 2 lớp trùng giờ cùng phòng
o	Hệ thống cảnh báo realtime
________________________________________
1.3 Tích hợp học Online
•	Mỗi lớp có:
o	1 link Zoom / Google Meet
•	Mỗi buổi học:
o	Nút “Bắt đầu dạy”
o	Student click → vào phòng
________________________________________
2. Quản lý buổi học (Session)
2.1 Định nghĩa
Buổi học là đơn vị nghiệp vụ trung tâm.
Mỗi buổi học gồm:
•	Ngày – giờ
•	Lớp
•	Hình thức
•	Nội dung
•	Tài nguyên
________________________________________
2.2 Buổi học Video (Self-learning)
•	Teacher gắn:
o	Link video
o	Bài tập
•	Student:
o	Xem
o	Bấm “Hoàn thành”
•	Teacher theo dõi tiến độ
________________________________________
3. Điểm danh (Attendance)
3.1 Offline
•	Tick thủ công
•	QR Code (PRO+):
o	Mỗi Student có QR riêng
o	Teacher quét bằng app
________________________________________
3.2 Online
•	Student vào từ hệ thống
•	Ghi nhận:
o	Thời điểm vào
•	Teacher xác nhận có mặt
________________________________________
3.3 Sau điểm danh
•	Vắng không phép → gửi thông báo phụ huynh
________________________________________
4. Bài kiểm tra & Chấm điểm
4.1 Trắc nghiệm Online
•	Tạo đề
•	Student làm
•	Tự chấm
•	Lưu điểm – thống kê
4.2 Tự luận / Offline
•	Nhập điểm
•	Nhận xét
•	Nhập nhanh dạng bảng
________________________________________
5. Tài chính – Học phí
5.1 Cấu hình học phí
•	Theo:
o	Tháng
o	Buổi
o	Khóa
•	Có thể:
o	Tính tự động theo điểm danh
________________________________________
5.2 Thu học phí
Student:
•	Upload bill
•	“Đã chuyển khoản”
Teacher:
•	Xác nhận
•	Ghi trạng thái
________________________________________
5.3 Theo dõi chi phí
•	Ghi chi phí
•	Upload hóa đơn
•	Tính lợi nhuận cuối tháng
________________________________________
V. NGHIỆP VỤ ROLE STUDENT
1. Dashboard
•	Thời khóa biểu
•	Thông báo
•	Trạng thái học phí
2. Học tập
•	Vào lớp
•	Xem video
•	Tải tài liệu
3. Thi cử
•	Làm bài
•	Xem điểm
4. Học phí
•	Lịch sử
•	Upload bill
________________________________________
VI. MODULE THÔNG MINH & TƯƠNG TÁC
1. Báo cáo
•	Teacher:
o	Tỷ lệ đi học
o	Điểm số
•	Phụ huynh:
o	Phiếu liên lạc PDF
________________________________________
2. Nhắc lịch
•	Trước buổi học 30 phút
•	App / Email
________________________________________
3. Bảng tin lớp
•	Teacher đăng bài
•	Student / phụ huynh tương tác
________________________________________
VII. BẢNG GIÁ DỊCH VỤ (CHỐT)
Tính năng	STARTER	TEACHER	CENTER
Học sinh	≤30	≤150	∞
Lớp	≤3	≤10	∞
Hybrid	❌	✔	✔
QR	❌	✔	✔
Video LMS	❌	✔	✔
Thi trắc nghiệm	✔	✔	✔
Tài chính	Cơ bản	Nâng cao	Kế toán
Báo cáo	Cơ bản	Chi tiết	Trung tâm
________________________________________
VIII. KẾT LUẬN
•	✅ Nghiệp vụ đầy đủ – logic – triển khai được
•	✅ Chuẩn SaaS – Multi-tenant
•	✅ Phù hợp thị trường Việt Nam
•	✅ Mở rộng LMS / AI / Kế toán

