document.addEventListener('DOMContentLoaded', function() {
    const editBtns = document.querySelectorAll('.edit-btn');
    const deleteBtns = document.querySelectorAll('.delete-btn');
    const saveEditBtn = document.getElementById('saveEditBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    let currentCartItemID = null; // ตัวแปรเก็บ Cart_itemID ที่กำลังถูกแก้ไขหรือลบ

    // Handle Edit Button Click
    editBtns.forEach(function(button) {
        button.addEventListener('click', function() {
            const cartItemID = this.dataset.id; // รับ Cart_itemID จาก attribute data-id
            const currentQuantity = this.dataset.quantity; // รับปริมาณสินค้าจาก attribute data-quantity

            const row = this.closest('.cart-item'); // ไปที่แถวปัจจุบัน
            const stockCell = row.querySelector('.item-stock'); // หา cell ที่มี stock
            const maxStock = parseInt(stockCell.textContent); // ดึง stock

            const priceCell = row.querySelector('.unit-price'); // หา cell ที่มีราคา
            const unitPrice = parseFloat(priceCell.textContent.replace(/[^0-9.-]+/g, "")); // ดึงราคา ต่อชิ้นจาก data-attribute

            console.log('Edit Clicked: CartItemID =', cartItemID, 'Stock =', maxStock, 'Price =', unitPrice); // แสดง CartItemID, Stock, Price

            const editQuantityInput = document.getElementById('editQuantity');
            editQuantityInput.value = currentQuantity;
            editQuantityInput.setAttribute('min', 1);
            editQuantityInput.setAttribute('max', maxStock);
            editQuantityInput.dataset.stock = maxStock;
            
            // แสดงค่าปริมาณใน input สำหรับการแก้ไข
            document.getElementById('editQuantity').value = currentQuantity;
            currentCartItemID = cartItemID; // เก็บ Cart_itemID ไว้

            // แสดง Modal สำหรับการแก้ไข
            document.getElementById('editModal').style.display = 'flex';
        });
    });

    // Handle Delete Button Click
    deleteBtns.forEach(function(button) {
        button.addEventListener('click', function() {
            const cartItemID = this.dataset.id; // รับ Cart_itemID จาก attribute data-id
            console.log('Delete Clicked: CartItemID =', cartItemID);
            
            currentCartItemID = cartItemID; // เก็บ Cart_itemID ไว้สำหรับการลบ

            // แสดง Modal สำหรับยืนยันการลบ
            document.getElementById('deleteModal').style.display = 'flex';
        });
    });

    // Handle Cancel Edit (เมื่อกด Cancel ในการแก้ไข)
    cancelEditBtn.addEventListener('click', function() {
        document.getElementById('editModal').style.display = 'none'; // ซ่อน Modal แก้ไข
    });

    // Handle Cancel Delete (เมื่อกด Cancel ในการลบ)
    cancelDeleteBtn.addEventListener('click', function() {
        document.getElementById('deleteModal').style.display = 'none'; // ซ่อน Modal ลบ
    });

    // Handle Save Changes for Edit (เมื่อกด Save ในการแก้ไขจำนวน)
    saveEditBtn.addEventListener('click', function() {
        const newQuantity = document.getElementById('editQuantity').value.trim(); // รับปริมาณใหม่จาก input
    
        if (newQuantity !== '' && currentCartItemID) {
            const formData = new FormData();
            formData.append('cart_item_id', currentCartItemID); // ส่ง cart_item_id
            formData.append('new_quantity', newQuantity); // ส่งปริมาณใหม่
            formData.append('edit_cart', true); // ระบุว่าเป็นคำขอแก้ไข
    
            // ส่งคำขอไปยัง cart.php เพื่ออัปเดตข้อมูล
            fetch('update_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('editModal').style.display = 'none'; // ปิด Modal
    
                // อัปเดตข้อมูลใน DOM (เฉพาะสินค้าที่ถูกแก้ไข)
                const cartItem = document.querySelector(`[data-id="${currentCartItemID}"]`).closest('.cart-item');
                if (cartItem) {
                    cartItem.querySelector('.item-quantity').textContent = newQuantity;
    
                    // ดึงราคาต่อหน่วยจาก data-unitPrice attribute
                    const unitPrice = parseFloat(cartItem.querySelector('.unit-price').textContent.replace(/[^0-9.-]+/g, ""));
                    
                    // คำนวณราคาทั้งหมดใหม่
                    const totalPrice = unitPrice * newQuantity;
    
                    // อัปเดตราคาทั้งหมดใน DOM
                    const priceElement = cartItem.querySelector('.total-item-price');  
                    if (priceElement) {
                        priceElement.textContent = `Total Price: ${totalPrice.toFixed(2)} THB`;

                        const checkbox = cartItem.querySelector('.item-checkbox');  
                        if (checkbox) {
                            checkbox.dataset.price = totalPrice; 
                        }

                        calculateTotal();  
                    }
                }
            })
            .catch(error => console.error('Error updating cart:', error));
        } else {
            alert("กรุณาใส่จำนวนใหม่ให้ถูกต้อง");
        }
    });            

    // Handle Delete Confirmation (เมื่อกด Yes ในการลบ)
    confirmDeleteBtn.addEventListener('click', function() {
        if (currentCartItemID) {
            const formData = new FormData();
            formData.append('cart_item_id', currentCartItemID); // ส่ง cart_item_id ของสินค้าที่จะลบ
            formData.append('delete_cart', true); // ระบุว่าเป็นคำขอลบ

            // ส่งคำขอลบไปยัง cart.php
            fetch('delete_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('deleteModal').style.display = 'none'; // ปิด Modal
                // ลบสินค้าจาก DOM
                const cartItem = document.querySelector(`[data-id="${currentCartItemID}"]`).closest('.cart-item');
                cartItem.remove();  // ลบรายการสินค้าออกจาก DOM
            })
            .catch(error => console.error('Error:', error));
        }
    });

    // ฟังก์ชันปิด Modal
    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
        document.getElementById('deleteModal').style.display = 'none';
    }
});
