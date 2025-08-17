
// Clone konten ke iPhone screen
function cloneContent() {
    const mainContent = document.getElementById('main-content');
    const iphoneContent = document.getElementById('iphone-content');

    // Clone konten ke iPhone screen
    iphoneContent.innerHTML = mainContent.innerHTML;
    
    // Setup mobile touch events untuk project cards
    setupMobileProjectCards();
    
    // Setup mobile touch events untuk tombol project
    setupMobileProjectButtons();

    // Setup Share Modal setelah konten di-clone
    setupShareModal();
}

// Setup mobile touch events untuk project cards
function setupMobileProjectCards() {
    const projectCards = document.querySelectorAll('.project-card');
    
    projectCards.forEach(card => {
        let isActive = false;
        
        // Simple click event untuk mobile
        card.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                // Jika yang diklik adalah tombol project, jangan prevent default
                if (e.target.classList.contains('project-btn') || e.target.closest('.project-btn')) {
                    return; // Biarkan tombol project berfungsi normal
                }
                
                e.preventDefault();
                
                // Toggle active state
                isActive = !isActive;
                
                // Hapus active class dari semua card lain
                projectCards.forEach(otherCard => {
                    if (otherCard !== card) {
                        otherCard.classList.remove('mobile-active');
                    }
                });
                
                // Toggle active class pada card yang diklik
                if (isActive) {
                    card.classList.add('mobile-active');
                } else {
                    card.classList.remove('mobile-active');
                }
                
                // Auto-hide setelah 3 detik
                if (isActive) {
                    setTimeout(() => {
                        card.classList.remove('mobile-active');
                        isActive = false;
                    }, 3000);
                }
            }
        });
    });
    
    // Close overlay saat tap di luar project cards
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            if (!e.target.closest('.project-card')) {
                projectCards.forEach(card => {
                    card.classList.remove('mobile-active');
                });
            }
        }
    });
    
    // Close overlay saat scroll di mobile
    window.addEventListener('scroll', function() {
        if (window.innerWidth <= 768) {
            projectCards.forEach(card => {
                card.classList.remove('mobile-active');
            });
        }
    });
}

// Setup mobile touch events untuk tombol project
function setupMobileProjectButtons() {
    const projectButtons = document.querySelectorAll('.project-btn');
    
    projectButtons.forEach(button => {
        // Tambahkan touch-action dan pointer-events untuk mobile
        if (window.innerWidth <= 768) {
            button.style.touchAction = 'manipulation';
            button.style.webkitTapHighlightColor = 'transparent';
            button.style.pointerEvents = 'auto';
            button.style.zIndex = '20';
        }
        
        // Event listener untuk memastikan tombol bisa diklik
        button.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                // Pastikan event tidak di-block
                e.stopPropagation();
                console.log('Project button clicked:', this.href);
            }
        });
        
        // Touch events untuk mobile
        button.addEventListener('touchstart', function(e) {
            if (window.innerWidth <= 768) {
                this.style.transform = 'scale(0.95)';
            }
        });
        
        button.addEventListener('touchend', function(e) {
            if (window.innerWidth <= 768) {
                this.style.transform = 'scale(1)';
            }
        });
    });
}

// Jalankan saat halaman dimuat
document.addEventListener('DOMContentLoaded', cloneContent);

// ===== Share Modal =====
function setupShareModal() {
    const iphoneScreen = document.querySelector('.iphone-screen');
    if (!iphoneScreen) return;

    // Buat overlay jika belum ada
    let overlay = iphoneScreen.querySelector('.share-overlay');
    if (!overlay) {
        overlay = createShareOverlay();
        iphoneScreen.appendChild(overlay);
    }

    const openButtons = document.querySelectorAll('#iphone-content .template-btn');
    openButtons.forEach(btn => {
        btn.addEventListener('click', () => openShareModal());
    });

    // Close handler: klik backdrop atau tombol X
    overlay.addEventListener('click', (e) => {
        if (e.target.classList.contains('share-overlay')) {
            closeShareModal();
        }
    });
    const closeBtn = overlay.querySelector('.share-close');
    if (closeBtn) closeBtn.addEventListener('click', closeShareModal);

    // Action buttons
    overlay.querySelector('[data-share="whatsapp"]').addEventListener('click', () => handleShare('whatsapp'));
    overlay.querySelector('[data-share="facebook"]').addEventListener('click', () => handleShare('facebook'));
    overlay.querySelector('[data-share="twitter"]').addEventListener('click', () => handleShare('twitter'));
    overlay.querySelector('[data-share="telegram"]').addEventListener('click', () => handleShare('telegram'));
    overlay.querySelector('[data-share="linkedin"]').addEventListener('click', () => handleShare('linkedin'));
    overlay.querySelectorAll('[data-share="copy"]').forEach(el => el.addEventListener('click', handleCopyLink));
    overlay.querySelectorAll('[data-share="native"]').forEach(el => el.addEventListener('click', handleNativeShare));
}

function createShareOverlay() {
    const overlay = document.createElement('div');
    overlay.className = 'share-overlay';
    overlay.innerHTML = `
        <div class="share-sheet">
            <div class="share-header">
                <div class="share-title">Bagikan</div>
                <button class="share-close" aria-label="Tutup">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="share-grid">
                <a class="share-action" data-share="whatsapp" href="javascript:void(0)">
                    <div class="icon"><i class="fab fa-whatsapp"></i></div>
                    <div class="share-label">WhatsApp</div>
                </a>
                <a class="share-action" data-share="facebook" href="javascript:void(0)">
                    <div class="icon"><i class="fab fa-facebook-f"></i></div>
                    <div class="share-label">Facebook</div>
                </a>
                <a class="share-action" data-share="twitter" href="javascript:void(0)">
                    <div class="icon"><i class="fab fa-twitter"></i></div>
                    <div class="share-label">Twitter</div>
                </a>
                <a class="share-action" data-share="telegram" href="javascript:void(0)">
                    <div class="icon"><i class="fab fa-telegram-plane"></i></div>
                    <div class="share-label">Telegram</div>
                </a>
                <a class="share-action" data-share="linkedin" href="javascript:void(0)">
                    <div class="icon"><i class="fab fa-linkedin-in"></i></div>
                    <div class="share-label">LinkedIn</div>
                </a>
                <a class="share-action" data-share="copy" href="javascript:void(0)">
                    <div class="icon"><i class="fa-solid fa-link"></i></div>
                    <div class="share-label">Salin Link</div>
                </a>
                <a class="share-action" data-share="native" href="javascript:void(0)">
                    <div class="icon"><i class="fa-solid fa-share-from-square"></i></div>
                    <div class="share-label">Share</div>
                </a>
            </div>
            <div class="share-footer">
                <button class="share-btn" type="button" data-share="copy">Salin</button>
                <button class="share-btn primary" type="button" data-share="native">Bagikan</button>
            </div>
        </div>
    `;
    return overlay;
}

function openShareModal() {
    const iphoneScreen = document.querySelector('.iphone-screen');
    const overlay = iphoneScreen?.querySelector('.share-overlay');
    if (!overlay) return;
    overlay.classList.add('is-open');
    iphoneScreen.classList.add('modal-open');
}

function closeShareModal() {
    const iphoneScreen = document.querySelector('.iphone-screen');
    const overlay = iphoneScreen?.querySelector('.share-overlay');
    if (!overlay) return;
    overlay.classList.remove('is-open');
    iphoneScreen.classList.remove('modal-open');
}

function getShareData() {
    const url = window.location.href;
    const title =
        document.querySelector('meta[property="og:title"]')?.content ||
        document.querySelector('meta[name="twitter:title"]')?.content ||
        document.title || 'Bagikan';
    const text =
        document.querySelector('meta[property="og:description"]')?.content ||
        document.querySelector('meta[name="twitter:description"]')?.content ||
        'Lihat profil dan tautan menarik ini';
    return { url, title, text };
}

function handleShare(platform) {
    const { url, title, text } = getShareData();
    let shareUrl = '';

    switch (platform) {
        case 'whatsapp':
            shareUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(`${title}\n${url}`)}`;
            break;
        case 'facebook':
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
            break;
        case 'twitter':
            shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`;
            break;
        case 'telegram':
            shareUrl = `https://t.me/share/url?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`;
            break;
        case 'linkedin':
            shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`;
            break;
        default:
            return;
    }

    window.open(shareUrl, '_blank', 'noopener,noreferrer');
}

async function handleCopyLink() {
    const { url } = getShareData();
    try {
        await navigator.clipboard.writeText(url);
        toast('Link disalin');
    } catch (e) {
        // Fallback
        const input = document.createElement('input');
        input.value = url;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);
        toast('Link disalin');
    }
}

async function handleNativeShare() {
    const data = getShareData();
    if (navigator.share) {
        try {
            await navigator.share({ title: data.title, text: data.text, url: data.url });
        } catch (_) {
            // Silent cancel/err
        }
    } else {
        handleShare('whatsapp');
    }
}

function toast(message) {
    const node = document.createElement('div');
    node.textContent = message;
    node.style.position = 'absolute';
    node.style.left = '50%';
    node.style.bottom = '90px';
    node.style.transform = 'translateX(-50%)';
    node.style.background = '#111';
    node.style.color = '#fff';
    node.style.padding = '8px 12px';
    node.style.borderRadius = '10px';
    node.style.fontWeight = '800';
    node.style.fontSize = '12px';
    node.style.zIndex = '1000';

    const iphoneScreen = document.querySelector('.iphone-screen');
    (iphoneScreen || document.body).appendChild(node);
    setTimeout(() => node.remove(), 1600);
}
