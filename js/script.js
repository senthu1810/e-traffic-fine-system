function showForm(role) {
    document.getElementById('userForm').style.display = role === 'user' ? 'block' : 'none';
    document.getElementById('policeForm').style.display = role === 'police' ? 'block' : 'none';
  }
  