function searchAndHighlight(inputId, rowClass, idField, contentFields) {
  const input = document.getElementById(inputId).value.toLowerCase().trim();
  const rows = document.querySelectorAll(`.${rowClass}`);
  let found = false;

  rows.forEach(row => {
    row.classList.remove('highlight');
    const text = contentFields.map(sel => row.querySelector(sel)?.textContent || '').join(' ').toLowerCase();
    if (text.includes(input)) {
      row.scrollIntoView({ behavior: 'smooth', block: 'center' });
      row.classList.add('highlight');
      found = true;
    }
  });

  if (!found) {
    alert("Invalid Details");
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const setupSearch = (inputId, rowClass, fields) => {
    const input = document.getElementById(inputId);
    if (input) {
      input.addEventListener("change", () =>
        searchAndHighlight(inputId, rowClass, "", fields)
      );
    }
  };

  setupSearch("userSearch", "user-row", ["div"]);
  setupSearch("officerSearch", "officer-row", ["div"]);
  setupSearch("paymentSearch", "payment-row", ["p"]);
  setupSearch("vehicleSearch", "vehicle-row", ["div"]);

  // NIC-based user auto-fill setup
  const nicInput = document.getElementById('nicSearch');
  const suggestionBox = document.getElementById('nicSuggestions');
  const userIdHidden = document.getElementById('userIdHidden');

  if (nicInput && suggestionBox && userIdHidden) {
    nicInput.addEventListener('input', () => {
      const inputVal = nicInput.value.trim().toLowerCase();
      suggestionBox.innerHTML = '';

      if (inputVal.length < 3) return;

      const filtered = window.allUsers.filter(user =>
        user.nic.toLowerCase().includes(inputVal)
      );

      filtered.forEach(user => {
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'list-group-item list-group-item-action';
        item.textContent = `${user.first_name} ${user.last_name} (${user.nic})`;
        item.onclick = () => {
          nicInput.value = user.nic;
          userIdHidden.value = user.id;
          suggestionBox.innerHTML = '';
        };
        suggestionBox.appendChild(item);
      });
    });

    document.addEventListener('click', (e) => {
      if (!nicInput.contains(e.target) && !suggestionBox.contains(e.target)) {
        suggestionBox.innerHTML = '';
      }
    });
  }
});

// Validation function for form submission
function validateAssignForm() {
  const userId = document.getElementById('userIdHidden').value;
  if (!userId) {
    alert("Please select a valid user from the suggestions.");
    return false;
  }
  return true;
}

document.getElementById("pendingFineSearch").addEventListener("change", () =>
  searchAndHighlight("pendingFineSearch", "pending-fine-row", "", ["p"])
);
