🟩 SPRINT 4 – ĐIỂM DANH ONLINE & OFFLINE (FINAL)
⏱ Thời lượng đề xuất: 2–3 tuần
🎯 Mục tiêu sprint
Xây dựng hệ thống điểm danh cho cả dạy Offline & Online, dựa trên Buổi học thực tế (Teaching Session), làm nền tảng bắt buộc cho:
•	Tính học phí
•	Báo cáo chuyên cần
•	Thống kê sau này
Sprint này tập trung ghi nhận sự có mặt, chưa tính tiền.
________________________________________
I. PHẠM VI SPRINT
1️⃣ Làm trong Sprint 4
•	Teaching Session (Buổi học)
•	Attendance record
•	Điểm danh Offline (tay + QR)
•	Điểm danh Online (click link)
•	Lịch sử & chỉnh sửa điểm danh
•	Lock buổi học
2️⃣ Chưa làm trong Sprint 4
•	Tính học phí
•	Thông báo phụ huynh
•	Báo cáo thống kê
•	Video bài giảng
•	Tích hợp Zoom API
________________________________________
II. KHÁI NIỆM NGHIỆP VỤ CỐT LÕI
________________________________________
1️⃣ TEACHING SESSION (BUỔI HỌC)
1.1 Định nghĩa
Teaching Session là:
•	Một buổi học thực tế
•	Sinh ra từ Class Schedule
•	Là đơn vị trung tâm cho:
o	Điểm danh
o	Học phí (Sprint 5)
📌 Không tạo trước hàng loạt
Session chỉ tồn tại khi giáo viên thực sự dạy buổi đó.
________________________________________
1.2 Thuộc tính Session
Trường	Mô tả
id	ID
class_id	Lớp
schedule_id	Lịch gốc
date	Ngày học
start_time	Giờ bắt đầu
end_time	Giờ kết thúc
session_type	ONLINE / OFFLINE / HYBRID
status	UPCOMING / ONGOING / COMPLETED
created_at	Ngày tạo
________________________________________
1.3 Quy tắc tạo Session
Session được tạo khi:
•	Teacher nhấn “Bắt đầu buổi học”
📌 Sprint 4:
•	Không auto tạo
•	Không background job
________________________________________
III. ATTENDANCE (BẢN GHI ĐIỂM DANH)
________________________________________
2️⃣ ATTENDANCE RECORD
2.1 Định nghĩa
Attendance là bản ghi:
•	1 học sinh
•	1 buổi học (Session)
________________________________________
2.2 Thuộc tính Attendance
Trường	Mô tả
id	ID
session_id	Buổi học
student_id	Học sinh
status	PRESENT / ABSENT / LATE
check_in_time	Thời gian vào
check_out_time	(future)
method	MANUAL / QR / ONLINE
note	Ghi chú
created_at	Ngày tạo
________________________________________
2.3 Nguyên tắc khởi tạo
•	Khi Session tạo:
o	Attendance chưa cần tạo trước
•	Khi:
o	Teacher tick tay
o	Student quét QR
o	Student click online
→ Attendance mới được tạo
📌 Student không có record = ABSENT ngầm định.
________________________________________
IV. ĐIỂM DANH OFFLINE
________________________________________
3️⃣ ĐIỂM DANH THỦ CÔNG
3.1 Luồng nghiệp vụ
Teacher → Bắt đầu buổi học
→ Session tạo
→ Hệ thống load danh sách học sinh
→ Teacher tick:
•	PRESENT
•	ABSENT
•	LATE
→ Lưu
________________________________________
3.2 Quy tắc
•	Mặc định là ABSENT
•	Teacher được chỉnh sửa trước & sau khi kết thúc
•	Chỉnh sửa phải ghi log
________________________________________
4️⃣ ĐIỂM DANH QR CODE
4.1 QR Code học sinh
•	Mỗi Student có 1 QR duy nhất
•	Nội dung:
o	student_id
o	checksum (hash + secret)
QR hiển thị trên:
•	App / web Student
________________________________________
4.2 Luồng quét QR
Teacher → Mở session
→ Bật chế độ quét
→ Camera quét QR
→ Hệ thống kiểm tra:
•	Student thuộc lớp?
•	Session đang ONGOING?
•	Chưa check-in?
→ Ghi:
•	status = PRESENT
•	method = QR
•	check_in_time = now
________________________________________
4.3 Chống gian lận
•	QR chỉ hợp lệ trong thời gian Session
•	Không quét trùng
•	Không check-in ngoài giờ
________________________________________
V. ĐIỂM DANH ONLINE
________________________________________
5️⃣ ONLINE ATTENDANCE
5.1 Nguyên tắc
Học sinh được tính có mặt khi:
•	Click “Vào học” từ hệ thống
•	Trong thời gian Session ONGOING
📌 Không theo dõi thời gian học thực.
________________________________________
5.2 Luồng xử lý
Student → Click link học
→ Check:
•	Session ONGOING?
•	Student thuộc lớp?
→ Tạo Attendance:
•	status = PRESENT
•	method = ONLINE
•	check_in_time
________________________________________
VI. KẾT THÚC BUỔI HỌC
________________________________________
6️⃣ KẾT SESSION
Teacher → Nhấn Kết thúc buổi học
Hệ thống:
•	Session.status → COMPLETED
•	Attendance bị LOCK
📌 Teacher có quyền unlock thủ công.
________________________________________
VII. LỊCH SỬ & CHỈNH SỬA
________________________________________
7️⃣ XEM LỊCH SỬ
Teacher xem:
•	Theo lớp
•	Theo học sinh
•	Theo ngày
________________________________________
8️⃣ LOG CHỈNH SỬA
Mỗi chỉnh sửa attendance phải ghi:
•	attendance_id
•	user_id
•	before / after
•	timestamp
________________________________________
VIII. PHÂN QUYỀN & KIỂM SOÁT
________________________________________
9️⃣ PHÂN QUYỀN
Role	Quyền
Teacher	CRUD attendance
Student	View của mình
Admin	Read-only
________________________________________
IX. YÊU CẦU KỸ THUẬT (BẮT BUỘC)
________________________________________
1️⃣ Backend
•	Laravel
•	Model: sessions, attendances
•	Policy kiểm tra ownership
•	Transaction khi tạo session + attendance
________________________________________
2️⃣ Frontend (NuxtJS)
•	Teacher: màn hình Session + QR
•	Student: nút Vào học
•	Realtime UI (polling)
________________________________________
3️⃣ Mobile (Capacitor – optional)
•	Quét QR camera
•	Student hiển thị QR
________________________________________
4️⃣ Hiệu năng
•	Index session_id, student_id
•	Không preload attendance toàn lớp
________________________________________
X. DEFINITION OF DONE – SPRINT 4
•	✅ Teaching Session
•	✅ Manual attendance
•	✅ QR attendance
•	✅ Online attendance
•	✅ Lock / unlock session
•	✅ Attendance history & log
________________________________________
XI. CHƯA LÀM
•	❌ Học phí
•	❌ Báo cáo
•	❌ Thông báo phụ huynh
•	❌ Zoom API
________________________________________
✅ SPRINT 4 – FINAL, CHUẨN FORMAT, SẴN SÀNG CODE

