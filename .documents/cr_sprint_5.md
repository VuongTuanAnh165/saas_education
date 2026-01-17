🟩 SPRINT 5 – HỌC PHÍ, CÔNG NỢ & THU CHI (FINAL)
⏱ Thời lượng đề xuất: 2–3 tuần
🎯 Mục tiêu Sprint
Xây dựng hệ thống học phí & tài chính cơ bản cho giáo viên, cho phép:
•	Cấu hình cách tính tiền cho từng lớp
•	Tự động / thủ công sinh công nợ
•	Ghi nhận học sinh đã đóng – còn nợ
•	Theo dõi thu – chi thực tế
⚠️ Sprint này KHÔNG có thanh toán online tự động
________________________________________
I. PHẠM VI SPRINT
1️⃣ Làm trong Sprint 5
•	Fee Plan (cấu hình học phí)
•	Invoice (công nợ học sinh)
•	Payment (ghi nhận đóng tiền)
•	Thu – chi nội bộ giáo viên
•	Dashboard tài chính cơ bản
2️⃣ Chưa làm trong Sprint 5
•	Thanh toán online / QR ngân hàng
•	Nhắc nợ tự động
•	Phụ huynh
•	Báo cáo tài chính nâng cao
________________________________________
II. KHÁI NIỆM NGHIỆP VỤ CỐT LÕI
________________________________________
1️⃣ FEE PLAN (CẤU HÌNH HỌC PHÍ)
1.1 Định nghĩa
Fee Plan là:
•	Cách tính học phí cho 1 Class
•	Áp dụng cho tất cả học sinh trong lớp đó
📌 Mỗi Class chỉ có 1 Fee Plan đang ACTIVE tại 1 thời điểm.
________________________________________
1.2 Các loại Fee Plan (THỰC TẾ)
🔹 PER SESSION (Theo buổi)
•	Giá: X / buổi
•	Tiền = số buổi PRESENT
Dùng cho:
•	Dạy kèm
•	Lớp học không cố định
________________________________________
🔹 MONTHLY (Theo tháng cố định)
•	Giá: X / tháng
•	Không phụ thuộc attendance
Dùng cho:
•	Trung tâm
•	Lớp học đều
________________________________________
🔹 COURSE (Theo khóa)
•	Giá: X / khóa
•	Chia theo thời gian / số buổi (future)
Sprint 5:
•	Chỉ ghi tổng tiền
•	Chưa chia chi tiết
________________________________________
1.3 Thuộc tính Fee Plan
Trường	Mô tả
id	ID
class_id	Lớp
type	PER_SESSION / MONTHLY / COURSE
price	Giá
currency	VND
start_date	Ngày hiệu lực
end_date	(optional)
created_at	Ngày tạo
📌 Không sửa Fee Plan đã phát sinh Invoice.
________________________________________
III. INVOICE – CÔNG NỢ HỌC SINH
________________________________________
2️⃣ INVOICE
2.1 Định nghĩa
Invoice là:
•	Công nợ học sinh
•	Gắn với:
o	Student
o	Class
o	Một kỳ tính tiền
📌 Invoice không phải hóa đơn VAT, chỉ là công nợ nội bộ.
________________________________________
2.2 Thuộc tính Invoice
Trường	Mô tả
id	ID
student_id	Học sinh
class_id	Lớp
period	VD: 01/2026
total_amount	Tổng tiền
paid_amount	Đã đóng
status	UNPAID / PARTIAL / PAID
created_at	Ngày tạo
________________________________________
IV. LOGIC TÍNH TIỀN (CỰC KỲ QUAN TRỌNG)
________________________________________
3️⃣ TÍNH TIỀN THEO BUỔI
3.1 Nguyên tắc
•	Chỉ tính:
o	Attendance = PRESENT
o	Session = COMPLETED
•	Snapshot attendance tại thời điểm tạo Invoice
________________________________________
3.2 Công thức
total_amount = số buổi PRESENT × giá / buổi
________________________________________
3.3 Thời điểm tạo Invoice
•	Teacher bấm:
“Tạo công nợ tháng X”
→ Hệ thống:
•	Lấy attendance
•	Tính tiền
•	Tạo invoice
📌 Không auto trong Sprint 5.
________________________________________
4️⃣ TÍNH TIỀN THEO THÁNG
•	Teacher tạo invoice thủ công
•	Mỗi tháng 1 invoice / lớp / học sinh
•	Không phụ thuộc attendance
________________________________________
V. PAYMENT – GHI NHẬN ĐÓNG TIỀN
________________________________________
5️⃣ PAYMENT RECORD
5.1 Định nghĩa
Payment là:
•	Một lần học sinh đóng tiền
•	Có thể:
o	Đóng đủ
o	Đóng nhiều lần
________________________________________
5.2 Thuộc tính Payment
Trường	Mô tả
id	ID
invoice_id	Công nợ
amount	Số tiền
method	CASH / TRANSFER
proof_image	Ảnh bill
confirmed_by	teacher_id
confirmed_at	Thời điểm
created_at	Ngày tạo
________________________________________
5.3 Luồng chuyển khoản
Student → Upload bill
→ Teacher kiểm tra
→ Confirm
→ Update paid_amount + status
📌 Payment không được xóa sau khi confirm.
________________________________________
VI. TRẠNG THÁI CÔNG NỢ
paid_amount	status
= 0	UNPAID
< total	PARTIAL
= total	PAID
📌 Invoice PAID:
•	Không cho sửa attendance
•	Không cho sửa amount
________________________________________
VII. THU – CHI NỘI BỘ (GIÁO VIÊN)
________________________________________
6️⃣ EXPENSE (CHI PHÍ)
6.1 Định nghĩa
Expense là:
•	Chi phí vận hành lớp
•	Do Teacher ghi nhận
________________________________________
6.2 Thuộc tính Expense
Trường	Mô tả
id	ID
teacher_id	Chủ
amount	Số tiền
category	RENT / ELECTRIC / OTHER
note	Ghi chú
proof_image	Ảnh
created_at	Ngày tạo
📌 Expense không được xóa, chỉ thêm mới.
________________________________________
VIII. DASHBOARD TÀI CHÍNH (BẢN ĐẦU)
Teacher xem:
•	Tổng thu (Payment)
•	Tổng chi (Expense)
•	Công nợ học sinh
•	Lợi nhuận tạm tính
📌 Không có biểu đồ.
________________________________________
IX. PHÂN QUYỀN & KIỂM SOÁT
Role	Quyền
Teacher	CRUD finance
Student	View invoice + upload bill
Admin	Read-only
________________________________________
X. YÊU CẦU KỸ THUẬT (BẮT BUỘC)
Backend
•	Laravel
•	Transaction khi:
o	Tạo Invoice
o	Confirm Payment
•	Không cho update hard-delete
Data Integrity
•	Snapshot attendance khi tạo invoice
•	Index:
o	student_id
o	class_id
o	period
Security
•	Check teacher_id mọi truy vấn
•	Không truy cập chéo invoice
________________________________________
XI. LUỒNG NGHIỆP VỤ MẪU
Luồng 1 – Theo buổi
Session COMPLETED
→ Teacher tạo invoice tháng
→ Hệ thống tính tiền
→ Student đóng
→ PAID
Luồng 2 – Theo tháng
Teacher tạo invoice
→ Student đóng nhiều lần
→ PARTIAL → PAID
________________________________________
XII. DEFINITION OF DONE – SPRINT 5
•	✅ Fee Plan
•	✅ Invoice
•	✅ Payment
•	✅ Thu – chi
•	✅ Dashboard tài chính
________________________________________
XIII. CHƯA LÀM
•	❌ Thanh toán online
•	❌ Nhắc nợ
•	❌ Phụ huynh
•	❌ Báo cáo nâng cao
________________________________________
✅ SPRINT 5 – FINAL, CHUẨN NỀN TÀI CHÍNH

