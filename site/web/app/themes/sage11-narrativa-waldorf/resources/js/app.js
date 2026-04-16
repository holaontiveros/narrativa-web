import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('mobileMenu', {
  open: false,
  toggle() {
    this.open = !this.open;
  },
});

Alpine.start();

// Share button functionality
// Check if native sharing is available
window.isNativeShareAvailable = function() {
  return navigator.share && navigator.canShare && navigator.canShare({
    title: document.title,
    url: window.location.href
  });
};

// Native share function
window.useNativeShare = function(customTitle = null) {
  if (navigator.share) {
    const shareData = {
      title: customTitle || document.title,
      url: window.location.href
    };

    // Add meta description as text if available
    const metaDescription = document.querySelector('meta[name="description"]');
    if (metaDescription && metaDescription.content) {
      shareData.text = metaDescription.content;
    }

    navigator.share(shareData).catch((error) => {
      console.log('Error sharing:', error);
    });
    return true;
  }
  return false;
};

window.shareOnX = function() {
  // Try native share first
  if (window.useNativeShare()) {
    return;
  }

  // Fallback to X-specific sharing
  const url = encodeURIComponent(window.location.href);
  const text = encodeURIComponent(document.title);
  const xUrl = `https://twitter.com/intent/tweet?url=${url}&text=${text}`;
  window.open(xUrl, 'x-share', 'width=600,height=400,resizable=yes,scrollbars=yes');
};

window.shareOnFacebook = function() {
  // Try native share first
  if (window.useNativeShare()) {
    return;
  }

  // Fallback to Facebook-specific sharing
  const url = encodeURIComponent(window.location.href);
  const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
  window.open(facebookUrl, 'facebook-share', 'width=600,height=400,resizable=yes,scrollbars=yes');
};

window.shareOnInstagram = function() {
  // Try native share first
  if (window.useNativeShare()) {
    return;
  }

  // Fallback: Instagram doesn't support direct URL sharing, so we'll copy the URL to clipboard
  // and provide instructions to the user
  navigator.clipboard.writeText(window.location.href).then(() => {
    alert('Link copied to clipboard! Open Instagram and paste it in your story or post.');
  }).catch(() => {
    // Fallback for older browsers
    const textArea = document.createElement('textarea');
    textArea.value = window.location.href;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand('copy');
    document.body.removeChild(textArea);
    alert('Link copied to clipboard! Open Instagram and paste it in your story or post.');
  });
};
