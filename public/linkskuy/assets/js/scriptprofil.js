'use strict';

// Global function for handling photo change - must be defined before HTML loads
window.handlePhotoChange = function(input) {
  console.log('Photo change triggered!'); // Debug log
  
  // Check if input has files
  if (!input.files || input.files.length === 0) {
    console.log('No files selected');
    return;
  }
  
  const file = input.files[0];
  console.log('File selected:', file.name, file.type, file.size);
  
  // File size validation (2MB)
  if (file.size > 2 * 1024 * 1024) {
    console.log('Ukuran file terlalu besar! Maksimal 2MB.');
    return;
  }
  
  // Check if it's an image
  if (!file.type.startsWith('image/')) {
    console.log('Pilih file gambar yang valid! Format: JPG, PNG, GIF');
    return;
  }
  
  // Method 1: Using URL.createObjectURL (faster preview)
  try {
    const profilePhotoImg = document.getElementById('profile-photo-img');
    console.log('Profile photo img element:', profilePhotoImg);
    
    if (profilePhotoImg) {
      // Create object URL for immediate preview
      const objectURL = URL.createObjectURL(file);
      profilePhotoImg.src = objectURL;
      console.log('Photo preview updated with object URL!');
      
      // Clean up the object URL after a delay
      setTimeout(() => {
        URL.revokeObjectURL(objectURL);
      }, 1000);
      
      // Show success message
      setTimeout(() => {
        console.log('Foto profil berhasil diperbarui!');
      }, 100);
    } else {
      console.error('Profile photo img element not found!');
      console.log('Error: Elemen foto profil tidak ditemukan!');
    }
  } catch (error) {
    console.error('Error with object URL method:', error);
    
    // Fallback to FileReader method
    console.log('Falling back to FileReader method...');
    const reader = new FileReader();
    
    reader.onload = function(e) {
      console.log('FileReader onload triggered');
      
      // Note: profilePhotoImg already declared above
      if (profilePhotoImg) {
        profilePhotoImg.src = e.target.result;
        console.log('Photo src updated with FileReader!');
        
        setTimeout(() => {
          console.log('Foto profil berhasil diperbarui!');
        }, 100);
      }
    };
    
    reader.onerror = function(e) {
      console.error('FileReader error:', e);
      console.log('Error membaca file!');
    };
    
    reader.readAsDataURL(file);
  }
};

// Profile Page Functions
document.addEventListener('DOMContentLoaded', function() {
  // Photo upload preview
  const profilePhotoInput = document.getElementById('profile-photo');
  const profilePhotoPreview = document.getElementById('profile-photo-preview');
  
  if (profilePhotoInput && profilePhotoPreview) {
    profilePhotoInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        if (file.type.startsWith('image/')) {
          const reader = new FileReader();
          reader.onload = function(e) {
            profilePhotoPreview.src = e.target.result;
          };
          reader.readAsDataURL(file);
        } else {
          console.log('Silakan pilih file gambar yang valid!');
        }
      }
    });
  }

  // Password toggle visibility - Improved version
  function setupPasswordToggles() {
    const passwordToggles = document.querySelectorAll('[data-password-toggle]');
    console.log('Setting up password toggles. Found:', passwordToggles.length);
    
    passwordToggles.forEach((toggle, index) => {
      // Remove any existing event listeners
      toggle.replaceWith(toggle.cloneNode(true));
      
      // Get the fresh toggle element
      const freshToggle = document.querySelectorAll('[data-password-toggle]')[index];
      
      freshToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const passwordInput = this.parentElement.querySelector('input[type="password"], input[type="text"]');
        const icon = this.querySelector('ion-icon');
        
        if (passwordInput && icon) {
          if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.name = 'eye-off-outline';
            console.log('Password unhidden for:', passwordInput.id);
          } else {
            passwordInput.type = 'password';
            icon.name = 'eye-outline';
            console.log('Password hidden for:', passwordInput.id);
          }
        } else {
          console.error('Password input or icon not found');
        }
      });
      
      console.log(`Toggle ${index + 1} setup complete`);
    });
  }
  
  // Call the function
  setupPasswordToggles();

  // Profile form submission
  const profileForm = document.querySelector('.profile-form');
  if (profileForm) {
    console.log('Profile form found, setting up submission handler');
    
    // Remove the preventDefault that was blocking form submission
    profileForm.addEventListener('submit', function(e) {
      console.log('Form submission triggered');
      
      // Validate form before submission
      const username = document.getElementById('username').value;
      const email = document.getElementById('email').value;
      const phone = document.getElementById('phone').value;
      const fullname = document.getElementById('fullname').value;
      
      // Basic validation
      if (!username || !email || !phone || !fullname) {
        e.preventDefault();
        console.log('Semua field harus diisi!');
        alert('Mohon lengkapi semua field yang wajib diisi!');
        return false;
      }
      
      if (!isValidEmail(email)) {
        e.preventDefault();
        console.log('Format email tidak valid!');
        alert('Format email tidak valid!');
        return false;
      }
      
      if (!isValidPhone(phone)) {
        e.preventDefault();
        console.log('Format nomor HP tidak valid!');
        alert('Format nomor HP tidak valid!');
        return false;
      }
      
      // If validation passes, allow form submission
      console.log('Form validation passed, submitting...');
      
      // Show loading state
      const saveBtn = this.querySelector('.save-profile-btn');
      if (saveBtn) {
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<ion-icon name="checkmark-outline"></ion-icon> Menyimpan...';
        saveBtn.disabled = true;
        
        // Re-enable button after a delay (in case of error)
        setTimeout(() => {
          saveBtn.innerHTML = originalText;
          saveBtn.disabled = false;
        }, 5000);
      }
    });
  }

  // Security form submission
  const securityForm = document.querySelector('.security-form');
  if (securityForm) {
    securityForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const currentPassword = document.getElementById('current-password').value;
      const newPassword = document.getElementById('new-password').value;
      const confirmPassword = document.getElementById('confirm-password').value;
      const newUsername = document.getElementById('new-username').value;
      
      // Basic validation
      if (!currentPassword) {
        console.log('Password saat ini harus diisi!');
        return;
      }
      
      if (newPassword && newPassword.length < 6) {
        console.log('Password baru minimal 6 karakter!');
        return;
      }
      
      if (newPassword && newPassword !== confirmPassword) {
        console.log('Konfirmasi password tidak cocok!');
        return;
      }
      
      if (newUsername && newUsername.length < 3) {
        console.log('Username baru minimal 3 karakter!');
        return;
      }
      
      // Simulate updating
      const updateBtn = this.querySelector('.update-security-btn');
      const originalText = updateBtn.innerHTML;
      updateBtn.innerHTML = '<ion-icon name="checkmark-outline"></ion-icon> Mengupdate...';
      updateBtn.disabled = true;
      
      setTimeout(() => {
        updateBtn.innerHTML = '<ion-icon name="checkmark-outline"></ion-icon> Terupdate!';
        setTimeout(() => {
          updateBtn.innerHTML = originalText;
          updateBtn.disabled = false;
          
          // Clear password fields
          document.getElementById('current-password').value = '';
          document.getElementById('new-password').value = '';
          document.getElementById('confirm-password').value = '';
          
          // Update current username if changed
          if (newUsername) {
            document.getElementById('current-username').value = newUsername;
            document.getElementById('new-username').value = '';
          }
        }, 2000);
      }, 1500);
      
      console.log('Keamanan akun berhasil diperbarui!');
    });
  }

  // Profile photo preview click functionality
  // Note: profilePhotoPreview and profilePhotoInput already declared above
  
  console.log('Looking for elements:', { 
    preview: profilePhotoPreview, 
    input: profilePhotoInput 
  }); // Debug log
  
  if (profilePhotoPreview && profilePhotoInput) {
    console.log('Profile photo elements found!'); // Debug log
    
    // Add click event to the preview container
    profilePhotoPreview.onclick = function(e) {
      e.preventDefault();
      e.stopPropagation();
      console.log('Photo preview clicked!'); // Debug log
      profilePhotoInput.click();
    };
    
    console.log('Event listeners added successfully!'); // Debug log
  } else {
    console.log('Profile photo elements not found:', { 
      preview: profilePhotoPreview, 
      input: profilePhotoInput 
    }); // Debug log
  }
  
  // Profile form submission (alternative version)
  const profileFormAlt = document.getElementById('profile-form');
  if (profileFormAlt) {
    profileFormAlt.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const fullname = document.getElementById('fullname').value;
      // Note: username, email, phone already declared above
      const oldPassword = document.getElementById('old-password').value;
      // Note: newPassword, confirmPassword already declared above
      
      // Basic validation
      if (!fullname || !username || !email || !phone) {
        console.log('Semua field wajib diisi!');
        return;
      }
      
      if (!isValidEmail(email)) {
        console.log('Format email tidak valid!');
        return;
      }
      
      if (!isValidPhone(phone)) {
        console.log('Format nomor HP tidak valid!');
        return;
      }
      
      // Password validation if changing password
      if (oldPassword || newPassword || confirmPassword) {
        if (!oldPassword) {
          console.log('Password lama harus diisi jika ingin mengganti password!');
          return;
        }
        
        if (!newPassword) {
          console.log('Password baru harus diisi jika ingin mengganti password!');
          return;
        }
        
        if (newPassword.length < 8) {
          console.log('Password baru minimal 8 karakter!');
          return;
        }
        
        if (newPassword !== confirmPassword) {
          console.log('Konfirmasi password tidak cocok!');
          return;
        }
      }
      
      // Simulate saving
      const saveBtn = document.querySelector('.save-profile-btn');
      const originalText = saveBtn.innerHTML;
      saveBtn.innerHTML = '<ion-icon name="checkmark-outline"></ion-icon> Menyimpan...';
      saveBtn.disabled = true;
      
      setTimeout(() => {
        saveBtn.innerHTML = '<ion-icon name="checkmark-outline"></ion-icon> Tersimpan!';
        setTimeout(() => {
          saveBtn.innerHTML = originalText;
          saveBtn.disabled = false;
        }, 2000);
      }, 1500);
      
      console.log('Profil berhasil diperbarui!');
    });
  }
  
  // Reset form functionality
  const resetBtn = document.getElementById('reset-form');
  if (resetBtn) {
    resetBtn.addEventListener('click', function() {
      profileFormAlt.reset();
      // Reset photo to default
      // Note: profilePhotoImg already declared above
      if (profilePhotoImg) {
        profilePhotoImg.src = './assets/images/my-avatar.png';
      }
      console.log('Form berhasil direset!');
    });
  }
  
  // Password toggle functionality
  const passwordToggles = document.querySelectorAll('.password-toggle');
  passwordToggles.forEach(toggle => {
    toggle.addEventListener('click', function() {
      const targetId = this.getAttribute('data-target');
      const targetInput = document.getElementById(targetId);
      const icon = this.querySelector('ion-icon');
      
      if (targetInput.type === 'password') {
        targetInput.type = 'text';
        icon.setAttribute('name', 'eye-off-outline');
      } else {
        targetInput.type = 'password';
        icon.setAttribute('name', 'eye-outline');
      }
    });
  });
});

// Helper functions
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

function isValidPhone(phone) {
  // Basic phone validation for Indonesian numbers
  const phoneRegex = /^(\+62|62|0)8[1-9][0-9]{6,9}$/;
  return phoneRegex.test(phone.replace(/\s|-/g, ''));
}
