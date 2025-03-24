/*
document.addEventListener('DOMContentLoaded', function() {
    const brandSelect = document.getElementById('brandSelect');
    const modelSelect = document.getElementById('modelSelect');
  
    
    const modelsByBrand = {
      Audi: ['A3', 'A4', 'A6'],
      BMW: ['X1', 'X3', 'X5'],
      Mercedes: ['C-Class', 'E-Class', 'S-Class'],
    };
  
    brandSelect.addEventListener('change', function() {
      const selectedBrand = this.value;
      
      modelSelect.innerHTML = '';
  
      
      const defaultOption = document.createElement('option');
      defaultOption.value = '';
      defaultOption.textContent = 'Choose Model';
      defaultOption.selected = true;
      modelSelect.appendChild(defaultOption);
  
      
      if (selectedBrand && modelsByBrand[selectedBrand]) {
        modelsByBrand[selectedBrand].forEach(function(model) {
          const option = document.createElement('option');
          option.value = model;
          option.textContent = model;
          modelSelect.appendChild(option);
        });
        modelSelect.disabled = false;
      } else {
        modelSelect.disabled = true;
      }
    });
  });
  */