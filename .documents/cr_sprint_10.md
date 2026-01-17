🟦 SPRINT 10 – ADMIN, BILLING, SCALE & GROWTH (FINAL)
⏱ Thời lượng đề xuất: 2–3 tuần
🎯 Mục tiêu sprint:
Xây dựng hệ thống quản trị SaaS giúp:
•	Chủ hệ thống kiểm soát giáo viên & doanh thu
•	Quản lý vòng đời subscription
•	Hỗ trợ bán hàng, gia hạn, khuyến mãi
•	Chuẩn bị nền tảng để scale lớn & vận hành dài hạn
📌 Sprint 10 KHÔNG tác động luồng dạy – học hằng ngày, chỉ phục vụ Admin & vận hành.
________________________________________
I. PHẠM VI SPRINT 10
✅ In Scope
•	Admin Dashboard (toàn hệ thống)
•	Quản lý Teacher & trạng thái
•	Subscription & Gia hạn thủ công
•	Trial (dùng thử)
•	Voucher & Khuyến mãi
•	Referral (giới thiệu cơ bản)
•	Audit Log & Support cơ bản
________________________________________
❌ Out of Scope
•	Payment gateway tự động (VNPay, Momo, Stripe)
•	Xuất hóa đơn VAT
•	CRM marketing / automation
•	Báo cáo tài chính kế toán chuyên sâu
________________________________________
II. ACTOR & PHÂN QUYỀN
Actor	Mô tả
Admin	Chủ hệ thống SaaS
Teacher	Người dùng trả phí
Support Staff (optional)	Nhân viên hỗ trợ
System	Tự động khoá/mở theo rule
________________________________________
III. KHÁI NIỆM NGHIỆP VỤ
1️⃣ ADMIN PANEL
Admin Panel là:
•	Công cụ quản trị – vận hành – bán hàng
•	Không dùng cho giảng dạy
•	Không thay thế CRM lớn
📌 Admin thao tác thủ công, kiểm soát chặt, ưu tiên minh bạch & log.
________________________________________
IV. ADMIN DASHBOARD (TỔNG QUAN)
2️⃣ DASHBOARD CHÍNH
2.1 Thông tin hiển thị
•	Tổng số Teacher
•	Teacher theo trạng thái:
o	ACTIVE
o	TRIAL
o	EXPIRED
o	LOCKED
•	Doanh thu:
o	Hôm nay
o	Tháng hiện tại
•	Top gói dịch vụ
•	Danh sách Teacher sắp hết hạn (≤ 7 ngày)
________________________________________
2.2 Quy tắc nghiệp vụ
•	Chỉ Admin truy cập
•	Dữ liệu tổng hợp từ:
o	teachers
o	subscriptions
o	payments
________________________________________
V. QUẢN LÝ GIÁO VIÊN
3️⃣ DANH SÁCH TEACHER
Admin có thể:
•	Xem danh sách teacher
•	Lọc theo:
o	Trạng thái
o	Gói dịch vụ
o	Ngày hết hạn
________________________________________
3.2 CHI TIẾT TEACHER
Admin xem:
•	Thông tin cơ bản
•	Gói đang sử dụng
•	Thời gian hiệu lực
•	Số lớp
•	Số học sinh
•	Lịch sử thanh toán
________________________________________
3.3 THAO TÁC ADMIN
Admin có thể:
•	Khoá teacher (LOCKED)
•	Mở khoá
•	Gia hạn (+ ngày)
•	Đổi gói dịch vụ
📌 Tác động dây chuyền:
•	Teacher bị LOCKED / EXPIRED
→ Student Portal bị khóa (Sprint 9)
________________________________________
VI. SUBSCRIPTION & BILLING
4️⃣ GIA HẠN DỊCH VỤ
4.1 Luồng gia hạn
1.	Admin mở chi tiết teacher
2.	Chọn:
o	Gói
o	Thời hạn (tháng / năm)
3.	Nhập:
o	Số tiền
o	Phương thức (chuyển khoản / tiền mặt)
4.	System:
o	Cộng ngày
o	Ghi payment record
________________________________________
4.2 Quy tắc nghiệp vụ
•	Không payment → không gia hạn
•	Gia hạn cộng dồn
•	Không cho sửa lịch sử payment
•	Mọi thay đổi phải có audit log
________________________________________
VII. TRIAL (DÙNG THỬ)
5️⃣ LOGIC TRIAL
•	Admin cấu hình:
o	Số ngày trial
o	Gói áp dụng
•	Teacher đăng ký mới:
o	Trạng thái = TRIAL
________________________________________
5.2 KẾT THÚC TRIAL
•	Hết trial:
o	Không thanh toán → EXPIRED
o	Thanh toán → ACTIVE
________________________________________
VIII. VOUCHER & KHUYẾN MÃI
6️⃣ TẠO VOUCHER
Admin tạo:
•	Code
•	Loại:
o	Giảm %
o	Giảm tiền cố định
•	Thời hạn
•	Giới hạn lượt dùng
📌 Không áp dụng stacking nhiều voucher.
________________________________________
6.2 ÁP DỤNG
•	Voucher dùng khi:
o	Gia hạn
o	Đăng ký mới
•	System:
o	Tự trừ tiền
o	Ghi log usage
________________________________________
IX. REFERRAL (GIỚI THIỆU)
7️⃣ LOGIC REFERRAL
•	Mỗi teacher có referral code
•	Teacher B nhập code của A khi đăng ký
________________________________________
7.2 PHẦN THƯỞNG
Admin cấu hình:
•	Tặng A:
o	X ngày sử dụng
o	Hoặc giảm tiền lần sau
📌 Không tạo ví tiền, chỉ cộng ngày / giảm giá.
________________________________________
X. LOG – AUDIT – SUPPORT
8️⃣ AUDIT LOG
Ghi log các hành động:
•	Admin:
o	Khoá / mở teacher
o	Gia hạn
o	Đổi gói
•	Teacher:
o	Xoá lớp
o	Xoá học sinh
________________________________________
8.2 SUPPORT
Admin có thể:
•	Tra cứu log lỗi cơ bản
•	Tìm theo teacher
•	Hỗ trợ nhanh khi có sự cố
________________________________________
XI. QUY TẮC NGHIỆP VỤ TỔNG HỢP
•	Admin có quyền cao nhất
•	Không ai ngoài Admin:
o	Gia hạn
o	Đổi gói
•	Subscription:
o	Luôn có log
o	Không chỉnh sửa ngược lịch sử
________________________________________
XII. DỮ LIỆU LIÊN QUAN
Entity	Mô tả
admins	tài khoản admin
teachers	trạng thái
subscriptions	gói
payments	lịch sử
vouchers	khuyến mãi
referrals	giới thiệu
audit_logs	nhật ký
________________________________________
XIII. YÊU CẦU KỸ THUẬT
Backend
•	Laravel
•	RBAC / Policy
•	Transaction khi gia hạn
________________________________________
Frontend
•	NuxtJS
•	Admin layout riêng
________________________________________
Bảo mật
•	Admin role bắt buộc
•	IP whitelist (optional)
•	Sẵn sàng 2FA (future)
________________________________________
XIV. DEFINITION OF DONE – SPRINT 10
Sprint 10 hoàn thành khi:
•	✅ Admin dashboard hoạt động
•	✅ Quản lý teacher đầy đủ
•	✅ Gia hạn, khoá/mở chính xác
•	✅ Trial, voucher, referral đúng logic
•	✅ Audit log đầy đủ
•	✅ Teacher & Student bị khóa đúng rule
________________________________________
XV. GIÁ TRỊ SAU SPRINT 10
👉 Sau Sprint 10, hệ thống:
•	✅ Là SaaS hoàn chỉnh
•	✅ Có thể bán & scale
•	✅ Dễ tích hợp payment gateway sau này
•	✅ Sẵn sàng vận hành lâu dài
________________________________________
✅ SPRINT 10 – FINAL – HOÀN TẤT

