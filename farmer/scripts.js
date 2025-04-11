document.addEventListener("DOMContentLoaded", () => {
  fetchPolicies();
  fetchSubsidies();
  fetchLoans();
});

function fetchPolicies() {
  fetch("policies.php")
    .then((response) => response.json())
    .then((data) => {
      const policyList = document.getElementById("policyList");
      policyList.innerHTML = ""; // Clear previous content
      data.forEach((policy) => {
        const div = document.createElement("div");
        div.className = "bg-white p-4 rounded shadow";
        div.innerHTML = `
                    <h3 class="text-lg font-bold">${policy.title}</h3>
                    <p>${policy.description}</p>
                    <p><strong>Type:</strong> ${policy.type}</p>
                    <p><strong>Region:</strong> ${
                      policy.region || "All Regions"
                    }</p>
                    <a href="${
                      policy.pdf_link
                    }" target="_blank" class="text-blue-500 hover:underline">Download PDF</a>
                `;
        policyList.appendChild(div);
      });
    });
}

function fetchSubsidies() {
  fetch("subsidies.php")
    .then((response) => response.json())
    .then((data) => {
      const subsidyList = document.getElementById("subsidyList");
      subsidyList.innerHTML = ""; // Clear previous content
      data.forEach((subsidy) => {
        const div = document.createElement("div");
        div.className = "bg-white p-4 rounded shadow";
        div.innerHTML = `
                    <h3 class="text-lg font-bold">${subsidy.title}</h3>
                    <p>${subsidy.description}</p>
                    <p><strong>Eligibility:</strong> ${subsidy.eligibility_criteria}</p>
                    <p><strong>How to Apply:</strong> ${subsidy.application_process}</p>
                `;
        subsidyList.appendChild(div);
      });
    });
}

function fetchLoans() {
  fetch("loans.php")
    .then((response) => response.json())
    .then((data) => {
      const loanList = document.getElementById("loanList");
      loanList.innerHTML = ""; // Clear previous content
      data.forEach((loan) => {
        const div = document.createElement("div");
        div.className = "bg-white p-4 rounded shadow";
        div.innerHTML = `
                    <h3 class="text-lg font-bold">${loan.purpose}</h3>
                    <p><strong>Amount Requested:</strong> ₹${loan.loan_amount}</p>
                    <p><strong>Status:</strong> ${loan.status}</p>
                `;
        loanList.appendChild(div);
      });
    });
}
