let list = document.querySelectorAll(".navigation li");

function activeLink() {
    list.forEach((item) => {
        item.classList.remove("hovered");
    });
    this.classList.add("hovered");
}

list.forEach((item) => item.addEventListener("mouseover", activeLink))

// Menu Toggle
let toggle = document.querySelector(".toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");

toggle.onclick = function() {
    navigation.classList.toggle("active");
    main.classList.toggle("active");
};

function toggleDescription(button) {
    var fullDescription = button.previousElementSibling.previousElementSibling;
    var shortDescription = button.previousElementSibling;

    // สลับการแสดงผลของคำอธิบายเต็มกับย่อ
    if (fullDescription.style.display === "none") {
        fullDescription.style.display = "inline";
        shortDescription.style.display = "none";
        button.textContent = "ย่อข้อความ";
    } else {
        fullDescription.style.display = "none";
        shortDescription.style.display = "inline";
        button.textContent = "ดูเพิ่มเติม";
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // ตรวจสอบ Quantity เมื่อมีการกรอก
    const quantityInputs = document.querySelectorAll('input[name^="items"][name$="[Quantity]"]');
    
    quantityInputs.forEach(input => {
        input.addEventListener('input', function() {
            const productId = input.name.match(/\[(.*?)\]/)[1];  // ดึง ProductID จากชื่อฟิลด์
            const stock = stockData[productId];  // ดึงข้อมูล stock จาก stockData
            const quantity = parseInt(input.value);
            
            if (quantity > stock) {
                alert('จำนวนสินค้าที่กรอกเกินจำนวนในสต็อก!');
                input.classList.add('error');
            } else {
                input.classList.remove('error');
            }            
        });
    });
});
