🟩 SPRINT 3 – QUẢN LÝ HỌC SINH & PHÂN QUYỀN THEO GIÁO VIÊN (FINAL – CHUẨN FORMAT)
⏱ Thời lượng đề xuất: 2–3 tuần
🎯 Mục tiêu sprint
Xây dựng hệ thống quản lý học sinh cho từng giáo viên, cho phép:
•	Giáo viên tạo & quản lý học sinh
•	Gán học sinh vào lớp
•	Học sinh đăng nhập và xem thông tin học tập
•	Kiểm soát truy cập và khóa dây chuyền theo trạng thái giáo viên / subscription
⚠️ Sprint này KHÔNG bao gồm:
Điểm danh, học phí, bài tập, video, phụ huynh, thông báo.
________________________________________
I. PHẠM VI SPRINT
1️⃣ Trong phạm vi Sprint 3
•	Student entity
•	Tài khoản & đăng nhập học sinh
•	Gán học sinh vào lớp (Enrollment)
•	Dashboard học sinh (read-only)
•	Phân quyền Teacher / Student
•	Giới hạn số học sinh theo gói
•	Cơ chế khóa dây chuyền theo Teacher
2️⃣ Ngoài phạm vi (để Sprint sau)
•	Điểm danh
•	Học phí
•	Video bài giảng
•	Bài tập
•	Phụ huynh
•	Thông báo
________________________________________
II. KHÁI NIỆM NGHIỆP VỤ CỐT LÕI
________________________________________
1️⃣ STUDENT (HỌC SINH)
1.1 Định nghĩa
Student là người học:
•	Do Teacher tạo và quản lý
•	Thuộc duy nhất 1 Teacher
•	Có thể học nhiều lớp của cùng Teacher
📌 Không cho học sinh học nhiều teacher
→ Phù hợp mô hình dạy thêm thực tế, đơn giản hoá phân quyền.
________________________________________
1.2 Thuộc tính Student
Trường	Mô tả
id	ID học sinh
teacher_id	Giáo viên chủ quản
full_name	Họ tên
phone	SĐT
email	Email (optional)
username	Tên đăng nhập
password_hash	Mật khẩu (hash)
status	ACTIVE / SUSPENDED
created_at	Ngày tạo
updated_at	Ngày cập nhật
________________________________________
1.3 Quy tắc nghiệp vụ
•	Student chỉ thuộc 1 Teacher
•	Teacher CRUD Student của mình
•	Admin chỉ read-only
•	Không cho truy cập chéo dữ liệu
________________________________________
III. TẠO & QUẢN LÝ HỌC SINH
________________________________________
2️⃣ TẠO HỌC SINH
2.1 Hình thức tạo
Sprint 3 chỉ hỗ trợ tạo thủ công.
Teacher nhập:
•	Họ tên
•	SĐT
•	Username (hệ thống gợi ý nếu trùng)
Hệ thống tự động:
•	Tạo password tạm (random)
•	Hash password
•	Đánh dấu force_change_password = true
📌 Import CSV để Sprint sau.
________________________________________
2.2 Kiểm tra điều kiện tạo
Khi tạo Student, hệ thống kiểm tra:
1.	Teacher.status = ACTIVE
2.	Subscription.status = ACTIVE
3.	total_students < max_students (theo gói)
→ Nếu fail → chặn tạo + thông báo rõ lý do
________________________________________
3️⃣ TRẠNG THÁI STUDENT
Trạng thái	Ý nghĩa
ACTIVE	Được đăng nhập
SUSPENDED	Bị khóa
📌 Student không có vòng đời độc lập, phụ thuộc Teacher.
________________________________________
IV. GÁN HỌC SINH VÀO LỚP
________________________________________
4️⃣ ENROLLMENT (GÁN LỚP)
4.1 Khái niệm
Enrollment là bảng trung gian:
•	Student ↔ Class
________________________________________
4.2 Thuộc tính Enrollment
Trường	Mô tả
id	ID
student_id	Học sinh
class_id	Lớp
joined_at	Ngày tham gia
status	ACTIVE / LEFT
________________________________________
4.3 Quy tắc nghiệp vụ
•	Student chỉ gán vào Class của Teacher mình
•	Không gán trùng
•	Không gán vào Class ARCHIVED
•	Không xóa enrollment (chỉ đổi status)
________________________________________
V. STUDENT LOGIN & DASHBOARD
________________________________________
5️⃣ ĐĂNG NHẬP HỌC SINH
5.1 Điều kiện đăng nhập
Cho phép login khi:
•	Student.status = ACTIVE
•	Teacher.status = ACTIVE
•	Subscription.status = ACTIVE
❌ Chỉ cần 1 điều kiện fail → chặn login
________________________________________
5.2 Thông báo khi bị khóa
Hiển thị:
“Lớp học đang tạm ngưng do giáo viên chưa gia hạn dịch vụ.
Vui lòng liên hệ giáo viên.”
________________________________________
6️⃣ STUDENT DASHBOARD
Student đăng nhập thấy:
•	Danh sách lớp đang học
•	Lịch học tuần
•	Link học online
•	Địa chỉ phòng học (offline)
📌 Chỉ đọc – không tương tác
________________________________________
VI. PHÂN QUYỀN & BẢO MẬT
________________________________________
7️⃣ PHÂN QUYỀN
Role	Quyền
Admin	Read-only
Teacher	CRUD student, enrollment
Student	Read-only dữ liệu của mình
________________________________________
8️⃣ NGĂN TRUY CẬP CHÉO
•	Teacher A ❌ xem Student B
•	Student ❌ truy cập API Teacher
•	Student ❌ sửa dữ liệu
________________________________________
VII. CƠ CHẾ KHÓA DÂY CHUYỀN
________________________________________
🔒 9️⃣ KHÓA THEO TEACHER
Nguyên tắc:
•	Không update hàng loạt Student khi Teacher bị khóa
•	Trạng thái Student được xác định runtime dựa vào Teacher
Khi Teacher ACTIVE lại:
•	Student tự động mở
📌 Không cần cron.
________________________________________
VIII. YÊU CẦU KỸ THUẬT (BẮT BUỘC – CHO DEV)
________________________________________
1️⃣ Backend
•	Laravel
•	Auth guard riêng cho Student
•	Policy / Middleware kiểm tra teacher ownership
•	Hash password chuẩn Laravel
•	Soft logic khóa theo Teacher
________________________________________
2️⃣ Database
•	students
•	enrollments
•	index theo teacher_id
•	unique(student_id, class_id)
________________________________________
3️⃣ Frontend (NuxtJS)
•	Student login page riêng
•	Student dashboard (read-only)
•	Teacher UI gán lớp
________________________________________
4️⃣ Bảo mật
•	API Student tách namespace
•	Token riêng cho Student
•	Không dùng chung token Teacher
________________________________________
IX. DEFINITION OF DONE – SPRINT 3
•	✅ CRUD Student
•	✅ Login Student
•	✅ Enrollment
•	✅ Dashboard Student
•	✅ Quota học sinh
•	✅ Phân quyền & khóa dây chuyền
________________________________________
X. CHƯA LÀM
•	❌ Điểm danh
•	❌ Học phí
•	❌ Video
•	❌ Phụ huynh
•	❌ Thông báo
________________________________________
✅ SPRINT 3 – FINAL, CHUẨN FORMAT, SẴN SÀNG CODE

