🟦 SPRINT 9 – STUDENT PORTAL & MOBILE APP (FINAL)
⏱ Thời lượng đề xuất: 2–3 tuần
🎯 Mục tiêu sprint:
Xây dựng Student Portal (Web + Mobile App) giúp học sinh:
•	Chủ động theo dõi lịch học
•	Truy cập buổi học online
•	Xem video bài giảng
•	Nhận thông báo & nhắc lịch
📌 Sprint 9 KHÔNG mở thêm nghiệp vụ mới, chỉ tiêu thụ dữ liệu từ Sprint 0–8.
________________________________________
I. PHẠM VI SPRINT 9
✅ Làm trong sprint này
•	Student login & xác thực
•	Student Dashboard (Web + Mobile)
•	Xem lịch học & buổi học
•	Truy cập link học online
•	Xem video bài giảng (LMS)
•	Nhận notification (In-app + Push basic)
•	Kiểm soát truy cập theo trạng thái Teacher
________________________________________
❌ Không làm trong sprint này
•	Thanh toán học phí online
•	Chat Student ↔ Teacher
•	Làm bài kiểm tra / quiz
•	Streaming video riêng
•	Comment / tương tác xã hội
________________________________________
II. ACTOR & VAI TRÒ
Actor	Vai trò
Admin	Không thao tác trực tiếp
Teacher	Cấp & quản lý quyền login student
Student	Người dùng chính
System	Kiểm tra trạng thái & phân quyền
________________________________________
III. KHÁI NIỆM NGHIỆP VỤ
1️⃣ STUDENT PORTAL
Student Portal là:
•	Giao diện dành riêng cho học sinh
•	Read-only (không tạo dữ liệu)
•	Truy cập dữ liệu thuộc teacher quản lý
📌 Dùng chung backend với Teacher Portal.
________________________________________
IV. QUẢN LÝ TÀI KHOẢN STUDENT
2️⃣ KÍCH HOẠT LOGIN STUDENT
2.1 Luồng kích hoạt
1.	Teacher vào Danh sách học sinh
2.	Chọn học sinh
3.	Bật “Cho phép đăng nhập”
4.	Hệ thống:
o	Sinh username (SĐT / email)
o	Sinh mật khẩu tạm
o	Gửi thông tin đăng nhập (email / in-app)
________________________________________
2.2 Quy tắc nghiệp vụ
•	Mỗi Student thuộc 1 Teacher duy nhất
•	Teacher có quyền:
o	Reset mật khẩu
o	Khoá / mở student
•	Nếu Teacher:
o	EXPIRED / LOCKED
→ Student tự động bị khóa
________________________________________
V. STUDENT LOGIN & BẢO MẬT
3️⃣ ĐĂNG NHẬP STUDENT
3.1 Điều kiện đăng nhập
Student login thành công khi:
•	Student.status = ACTIVE
•	Teacher.status = ACTIVE
•	Subscription = ACTIVE
❌ Chỉ cần 1 điều kiện fail → từ chối
________________________________________
3.2 Thông báo khi bị khóa
“Lớp học đang tạm ngưng do giáo viên chưa gia hạn dịch vụ.”
________________________________________
3.3 Bảo mật & phân quyền
•	Token riêng cho Student
•	Student:
o	❌ Không gọi API Teacher
o	❌ Không chỉnh sửa dữ liệu
o	✅ Chỉ đọc dữ liệu của mình
________________________________________
VI. STUDENT DASHBOARD
4️⃣ DASHBOARD CHÍNH
4.1 Nội dung hiển thị
•	Lịch học hôm nay
•	Buổi học sắp tới
•	Thông báo mới
•	Nút nhanh:
o	“Vào lớp Online”
o	“Xem video bài giảng”
________________________________________
4.2 Xem lịch học
Student:
1.	Mở tab Lịch học
2.	Xem theo:
o	Tuần
o	Lớp
3.	Click buổi học:
o	Online → mở link
o	Offline → xem địa điểm
________________________________________
VII. TRUY CẬP BUỔI HỌC ONLINE
5️⃣ VÀO LỚP ONLINE
5.1 Luồng xử lý
1.	Student nhấn “Vào lớp Online”
2.	System:
o	Kiểm tra session đang ACTIVE
o	Ghi log:
	check_in_time
3.	Redirect tới link Zoom / Meet
📌 Sprint 9:
•	KHÔNG tự tính attendance
•	Chỉ ghi log phục vụ Sprint 4
________________________________________
VIII. MODULE VIDEO BÀI GIẢNG (LMS)
6️⃣ DANH SÁCH VIDEO
Student thấy:
•	Video theo lớp
•	Theo buổi học
•	Trạng thái:
o	Chưa mở
o	Đã mở
o	Đã xem
________________________________________
6.2 Xem video
1.	Student chọn video
2.	System kiểm tra:
o	Thuộc class
o	Video PUBLISHED
o	Trong thời gian mở
3.	Load video từ:
o	YouTube (Unlisted)
o	Google Drive
o	External URL
📌 Sprint 9:
•	Tracking:
o	Mở video
o	Thời điểm xem
•	Không tracking % chi tiết
________________________________________
IX. THÔNG BÁO & NHẮC LỊCH
7️⃣ IN-APP NOTIFICATION
Trigger:
•	Reminder lịch học
•	Announcement từ teacher
•	Thông báo hệ thống
________________________________________
8️⃣ PUSH NOTIFICATION (MOBILE – BASIC)
8.1 Phạm vi
•	Nhắc lịch trước 30 phút
•	Thông báo teacher mở lớp online
📌 Sprint 9:
•	Push mức cơ bản
•	Không cá nhân hóa sâu
________________________________________
X. QUY TẮC NGHIỆP VỤ TỔNG HỢP
•	Student:
o	Không tạo dữ liệu
o	Không chỉnh sửa dữ liệu
•	Student bị khóa khi:
o	Teacher expired
o	Teacher locked
•	Mobile & Web dùng chung nghiệp vụ
________________________________________
XI. DỮ LIỆU & ENTITY LIÊN QUAN
Entity	Ghi chú
students	auth, status
student_logins	lịch sử login
enrollments	lớp học
sessions	lịch học
videos	LMS
notifications	thông báo
access_logs	vào/ra online
________________________________________
XII. YÊU CẦU KỸ THUẬT
Backend
•	Laravel API
•	Auth:
o	JWT hoặc Sanctum
•	Middleware phân quyền student
________________________________________
Frontend (Web)
•	NuxtJS
•	Student layout riêng
________________________________________
Mobile App
•	Capacitor
•	Wrap từ Nuxt build
•	Push notification (Firebase basic)
________________________________________
XIII. DEFINITION OF DONE – SPRINT 9
Sprint 9 hoàn thành khi:
•	✅ Student login thành công
•	✅ Xem được lịch học
•	✅ Vào lớp online
•	✅ Xem video bài giảng
•	✅ Nhận notification
•	✅ Bị khóa khi teacher hết hạn
________________________________________
XIV. GIÁ TRỊ SAU SPRINT 9
👉 Hệ thống:
•	Có Student App hoàn chỉnh
•	Giáo viên tăng giá trị dịch vụ
•	Sẵn sàng đưa ra thị trường thật
________________________________________
✅ SPRINT 9 – FINAL – HOÀN TẤT

