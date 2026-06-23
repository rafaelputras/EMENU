Created and Developed by Rafael Putra Santoso.

Here is the English version of your project notes, formatted professionally to serve as a README.md file or project documentation:

📌 Project Run Notes
This e-menu project is developed using the MVC (Model-View-Controller) architecture. To simplify navigation and system testing, here is the list of access links and user flow guidelines for your localhost environment.

1. Customer Flow
Customers access the system via their smartphones by scanning the QR Code available on each table.

Table Access Links:
🏠 Indoor Area

Table IND-1: http://localhost/emenu/public/order/?table=QR-IND-1

Table IND-2: http://localhost/emenu/public/order/?table=QR-IND-2

Table IND-3: http://localhost/emenu/public/order/?table=QR-IND-3

Table IND-4: http://localhost/emenu/public/order/?table=QR-IND-4

Table IND-5: http://localhost/emenu/public/order/?table=QR-IND-5

🌳 Outdoor Area

Table OUT-1: http://localhost/emenu/public/order/?table=QR-OUT-1

Table OUT-2: http://localhost/emenu/public/order/?table=QR-OUT-2

Table OUT-3: http://localhost/emenu/public/order/?table=QR-OUT-3

Table OUT-4: http://localhost/emenu/public/order/?table=QR-OUT-4

Table OUT-5: http://localhost/emenu/public/order/?table=QR-OUT-5

Ordering Steps:

Arrive at the Table: The customer sits at a table and scans the provided barcode (The system will redirect them to the specific table's URL).

View & Select Menu: The customer browses the menu catalog, selects a food/beverage category, and adds items to the cart.

Customization (Variants): When selecting a specific item (e.g., Coffee), the customer can adjust variant options like sugar level, extra toppings, and leave special notes.

Checkout: The customer opens the cart and processes the order. There are two payment options:

Online Payment: Processed immediately through the system.

Cash Payment: The customer will receive a billing barcode.

E-Receipt / Order Proof: The customer receives a Digital Receipt in the form of a QR Code or Order Number (e.g., ORD-2026...) with the instruction: "Show this QR to the Cashier".

2. Cashier / POS Flow
Cashier Access: http://localhost/emenu/public/cashier

Transaction Steps:

Receive Customer: Customers who choose the cash payment method go to the cashier and present their E-Receipt.

Scan Order Proof: The cashier clicks the "Scan QR" button in the top right corner, then scans or manually types the order number (ORD-xxx).

Confirm Order: The cashier's screen automatically displays the order details from the respective table, complete with tax calculations and the total bill.

Receive Payment: The cashier inputs the cash amount received from the customer (the system automatically calculates the change).

Confirm Payment: The cashier clicks the "Confirm & Print" button.

The system triggers a SweetAlert popup notification: "Payment Successful! Order Paid."

The order status in the database automatically updates to Paid.

3. Kitchen Display Flow
Kitchen / Chef Access: http://localhost/emenu/public/kitchen

Kitchen Operational Steps:

Incoming Orders: Once the cashier processes the payment and the status changes to Paid, the new order will automatically appear on the kitchen TV/Tablet screen.

Cooking Process: The chef can clearly see the order details, including the Table Number, Menu Name, and Variants/Notes to minimize serving errors.

Mark as Done: Once the dish is cooked and ready to be served by the waiter, the Chef clicks the "✅ Done Cooking" button.

Clear Queue: The order will disappear from the kitchen screen (Status updates to Completed / Ready), keeping the screen focused only on pending orders.

4. Admin & Management Flow
Admin Access: http://localhost/emenu/public/admin/dashboard
(Strictly accessible only by the Owner or Store Manager)

Key Admin Features:

Analytics (Dashboard): Monitor restaurant performance, daily sales charts, best-selling menus, and favorite customer variants.

Category Management: Add, edit, or hide categories (e.g., Food, Snacks, etc.) when items are unavailable.

Menu Management: Manage core product data, including food images, base prices, descriptions, and stock availability.

Variant Master: Configure Add-on groups (e.g., Creating a "Topping" group containing options like Boba +5k, Cheese +3k).
