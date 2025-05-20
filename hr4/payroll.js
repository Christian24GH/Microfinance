document.addEventListener("DOMContentLoaded", () => {
    const payrollForm = document.getElementById("payrollForm");
    const payrollTableBody = document.querySelector("#payrollTable tbody");
    let payrollData = [];

    // Load existing payroll data from local storage
    loadPayrollData();

    payrollForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const formData = new FormData(payrollForm);

    // Log formData key-values here (inside the event handler!)
    console.log("Sending formData:");
    for (const [key, value] of formData.entries()) {
        console.log(key + ":", value);
    }

       const grossPay = parseFloat(formData.get("gross_pay"));
    const deductions = parseFloat(formData.get("deductions"));

    if (isNaN(grossPay) || isNaN(deductions)) {
        alert("Please enter valid numbers for Gross Pay and Deductions.");
        return;
    }

        fetch("save_payroll.php", {
        method: "POST",
        body: formData
    })
         .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert(data.message);

            // Update localStorage and UI after success
            const newPayroll = {
                payrollId: formData.get("payroll_id") || generatePayrollId(),
                employeeId: formData.get("employee_id"),
                employeeName: formData.get("employee_name"),
                payPeriod: formData.get("pay_period"),
                grossPay,
                deductions,
                netPay: grossPay - deductions
            };

            if (formData.get("payroll_id")) {
                const index = payrollData.findIndex(p => p.payrollId === formData.get("payroll_id"));
                if (index !== -1) payrollData[index] = newPayroll;
            } else {
                payrollData.push(newPayroll);
            }

            savePayrollData();
            renderTable();
            payrollForm.reset();
            payrollForm.elements["payroll_id"].value = "";
            document.getElementById("submitButton").textContent = "Add Payroll";

        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error saving payroll:", error);
        alert("Failed to save payroll.");
    });
});
    function generatePayrollId() {
        return "PAY-" + (payrollData.length + 1).toString().padStart(4, "0");
    }

    function renderTable() {
        payrollTableBody.innerHTML = "";
        payrollData.forEach((payroll) => {
            const row = document.createElement("tr");

            row.innerHTML = `
                <td>${payroll.payrollId}</td>
                <td>${payroll.employeeId}</td>
                <td>${payroll.employeeName}</td>
                <td>${payroll.payPeriod}</td>
                <td>₱${payroll.grossPay.toFixed(2)}</td>
                <td>₱${payroll.deductions.toFixed(2)}</td>
                <td>₱${payroll.netPay.toFixed(2)}</td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="editPayroll('${payroll.payrollId}')">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="deletePayroll('${payroll.payrollId}')">Delete</button>
                </td>
            `;
            payrollTableBody.appendChild(row);
        });
    }

    function savePayrollData() {
        localStorage.setItem("payrollData", JSON.stringify(payrollData));
    }

    function loadPayrollData() {
        const savedData = localStorage.getItem("payrollData");
        if (savedData) {
            payrollData = JSON.parse(savedData);
            renderTable();
        }
    }

    window.deletePayroll = function (payrollId) {
        if (confirm("Are you sure you want to delete this payroll entry?")) {
            payrollData = payrollData.filter((payroll) => payroll.payrollId !== payrollId);
            savePayrollData();
            renderTable();
        }
    };

    window.editPayroll = function (payrollId) {
        const payroll = payrollData.find(p => p.payrollId === payrollId);
        if (payroll) {
            payrollForm.elements["payroll_id"].value = payroll.payrollId;
            payrollForm.elements["employee_id"].value = payroll.employeeId;
            payrollForm.elements["employee_name"].value = payroll.employeeName;
            payrollForm.elements["pay_period"].value = payroll.payPeriod;
            payrollForm.elements["gross_pay"].value = payroll.grossPay;
            payrollForm.elements["deductions"].value = payroll.deductions;
            document.getElementById("submitButton").textContent = "Update Payroll";
        }
    };
});
console.log("Sending formData:");
for (const [key, value] of formData.entries()) {
    console.log(key + ":", value);
}
