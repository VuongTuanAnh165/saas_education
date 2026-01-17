🟦 SPRINT 0 – NỀN TẢNG HỆ THỐNG & PHÂN QUYỀN
(Foundation & Identity Sprint)
⏱ Thời lượng đề xuất: 2 tuần
🎯 Tính chất: Sprint bắt buộc – sai Sprint 0 ⇒ toàn bộ hệ thống sau sai
________________________________________
I. MỤC TIÊU SPRINT (SPRINT GOAL)
Sprint 0 nhằm xây dựng nền móng cốt lõi cho hệ thống SaaS quản lý dạy thêm, bao gồm:
•	User & Role
•	Tenant (Teacher)
•	Authentication
•	Authorization
•	Tenant Isolation
•	Cơ chế khóa dây chuyền
⛔ Sprint 0 KHÔNG triển khai nghiệp vụ dạy học
⛔ Sprint 0 KHÔNG triển khai thanh toán
________________________________________
II. MỤC TIÊU NGHIỆP VỤ (BUSINESS GOAL)
Sau khi hoàn thành Sprint 0, hệ thống bắt buộc phải đảm bảo:
1.	Có 3 loại tài khoản có thể đăng nhập hệ thống:
o	Admin (chủ hệ thống)
o	Teacher (khách hàng SaaS)
o	Student (người dùng phụ thuộc Teacher)
2.	Dữ liệu của mỗi Teacher hoàn toàn tách biệt (multi-tenant)
3.	Có cơ chế khóa / mở tài khoản thủ công
4.	Đặt nền tảng kỹ thuật cho:
o	Subscription & Pricing
o	Giới hạn theo gói
o	Khóa dây chuyền Teacher → Student
________________________________________
III. ĐỊNH NGHĨA CÁC KHÁI NIỆM CỐT LÕI (CORE CONCEPTS)
1️⃣ User (Người dùng)
User là thực thể đăng nhập hệ thống.
Thuộc tính tối thiểu:
•	id
•	email (unique)
•	password (hashed)
•	role: ADMIN | TEACHER | STUDENT
•	status: ACTIVE | SUSPENDED
•	created_at
📌 Lưu ý quan trọng
•	Admin, Teacher, Student đều là User
•	Không có User “chung chung”, bắt buộc phải có role
________________________________________
2️⃣ Teacher (Tenant)
Teacher là khách hàng SaaS và cũng là ranh giới dữ liệu (tenant boundary).
Đặc điểm:
•	Mỗi Teacher có:
o	1 User (role = TEACHER)
o	1 không gian dữ liệu riêng
•	Tất cả dữ liệu nghiệp vụ trong hệ thống đều phải gắn teacher_id
📌 Nguyên tắc bất biến
Không tồn tại bất kỳ dữ liệu nghiệp vụ nào trong hệ thống mà không có teacher_id.
________________________________________
3️⃣ Student
Student là User phụ thuộc Teacher, không tồn tại độc lập.
Đặc điểm:
•	Có:
o	user_id
o	teacher_id
•	Không thể login nếu Teacher bị khóa
📌 Quy tắc cứng
Nếu Teacher bị khóa ⇒ toàn bộ Student thuộc Teacher đó bị khóa theo.
________________________________________
IV. PHẠM VI CHỨC NĂNG SPRINT 0
________________________________________
1️⃣ AUTHENTICATION – ĐĂNG NHẬP / ĐĂNG XUẤT
1.1 Đăng nhập
Luồng xử lý chuẩn:
1.	User nhập email + password
2.	Hệ thống:
o	Kiểm tra thông tin đăng nhập
o	Kiểm tra user.status
3.	Nếu role = TEACHER hoặc STUDENT:
o	Kiểm tra teacher.status
4.	Quyết định:
o	Cho phép login
o	Hoặc từ chối
Quy tắc bắt buộc:
•	user.status = SUSPENDED → ❌ từ chối login
•	role = STUDENT & teacher.status = SUSPENDED → ❌ từ chối login
________________________________________
1.2 Đăng xuất
•	Hủy token / session
•	Chuyển về màn hình login
________________________________________
1.3 Quên mật khẩu
•	User nhập email
•	Hệ thống gửi link reset password
•	User đặt mật khẩu mới
📌 Sprint 0:
•	Chỉ dùng email
•	Chưa cần OTP / SMS / bảo mật nâng cao
________________________________________
2️⃣ AUTHORIZATION – PHÂN QUYỀN
2.1 Phân quyền theo Role
Role	Quyền truy cập
ADMIN	Toàn bộ hệ thống
TEACHER	Dữ liệu của chính Teacher
STUDENT	Dữ liệu học tập cá nhân
________________________________________
2.2 Kiểm soát API & Page
Mỗi request bắt buộc kiểm tra:
•	User đã login chưa
•	Role có được phép truy cập không
•	Có đúng teacher_id không
📌 Nguyên tắc
Không có bất kỳ API nào được phép truy cập dữ liệu Teacher khác.
________________________________________
3️⃣ TENANT ISOLATION – CÁCH LY DỮ LIỆU
3.1 Nguyên tắc thiết kế
•	Mọi bảng nghiệp vụ (hiện tại & tương lai):
o	Đều phải có teacher_id
•	Mọi query:
o	Bắt buộc filter theo teacher_id từ context login
📌 teacher_id KHÔNG BAO GIỜ lấy từ request client.
________________________________________
3.2 Kiểm thử bắt buộc
•	Teacher A login
•	Teacher B login
•	Không thể:
o	Nhìn thấy user
o	Nhìn thấy student
o	Truy cập API của nhau
________________________________________
4️⃣ QUẢN LÝ TEACHER (ADMIN)
4.1 Admin tạo Teacher
Admin có chức năng:
•	Tạo Teacher với:
o	Email đăng nhập
o	Tên hiển thị
o	Trạng thái mặc định: ACTIVE
📌 Sprint 0:
•	Chưa gắn gói
•	Chưa cần payment
________________________________________
4.2 Admin khóa / mở Teacher
Khi Admin khóa Teacher:
•	teacher.status = SUSPENDED
•	Toàn bộ Student thuộc Teacher:
o	Không thể login
📌 Không xóa dữ liệu
________________________________________
5️⃣ QUẢN LÝ STUDENT (NỀN TẢNG)
5.1 Teacher tạo Student
Teacher có thể:
•	Tạo Student với:
o	Tên
o	Email (optional)
Hệ thống:
•	Tạo User role = STUDENT
•	Gán teacher_id
📌 Sprint 0:
•	Chưa gán lớp
•	Chưa gán khóa học
________________________________________
5.2 Student đăng nhập
•	Student login thành công
•	Thấy dashboard rỗng
•	Mục đích: test luồng hệ thống
________________________________________
V. LUỒNG NGHIỆP VỤ END-TO-END
Luồng 1 – Chuỗi tạo tài khoản
Admin tạo Teacher A
→ Teacher A login
→ Teacher A tạo Student X
→ Student X login
Luồng 2 – Khóa dây chuyền
Admin khóa Teacher A
→ Teacher A login ❌
→ Student X login ❌
________________________________________
VI. TRẠNG THÁI DỮ LIỆU (DATA STATE)
User.status
•	ACTIVE
•	SUSPENDED
Teacher.status
•	ACTIVE
•	SUSPENDED
📌 Chưa có:
•	EXPIRED
•	GRACE PERIOD
(để Sprint Subscription)
________________________________________
VII. YÊU CẦU KỸ THUẬT (TECHNICAL REQUIREMENTS)
1️⃣ Stack áp dụng cho Sprint 0
Layer	Công nghệ
Backend	Laravel
Frontend Web	NuxtJS
Database	MySQL / PostgreSQL
Auth	Token-based
📌 Sprint 0 KHÔNG dùng Capacitor
________________________________________
2️⃣ Backend – Yêu cầu bắt buộc
•	Middleware:
o	Check login
o	Check role
o	Check teacher status
•	Tenant isolation bắt buộc
•	Không xử lý phân quyền ở frontend
________________________________________
3️⃣ Frontend – Phạm vi
Màn hình tối thiểu:
•	Login
•	Quên mật khẩu
•	Admin: tạo Teacher
•	Teacher: tạo Student
•	Student: dashboard rỗng
📌 UI đơn giản, đúng luồng là đủ
________________________________________
VIII. DEFINITION OF DONE (Hoàn thành Sprint)
Sprint 0 được coi là hoàn thành khi:
•	✅ Admin / Teacher / Student login đúng
•	✅ Phân quyền hoạt động chính xác
•	✅ Tenant isolation tuyệt đối
•	✅ Khóa Teacher → khóa Student
•	✅ Không phát sinh dữ liệu mồ côi
________________________________________
IX. NHỮNG THỨ CỐ Ý CHƯA LÀM
•	❌ Subscription
•	❌ Pricing
•	❌ Payment
•	❌ Lớp học
•	❌ Video
•	❌ Attendance
👉 Cố ý loại bỏ để nền không gãy
________________________________________
🔒 KẾT LUẬN
Sprint 0 là “xương sống” của toàn bộ hệ thống SaaS.
Sai Sprint 0 = đập đi làm lại.
📌 Tài liệu này có thể giao trực tiếp cho dev để code Sprint 0.

