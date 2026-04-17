/**
 * Contact Page Interactions
 */
export function initContact() {
    const enquiryTypes = document.querySelectorAll('.enquiry-type');
    
    if (enquiryTypes.length > 0) {
        enquiryTypes.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove selected from all
                enquiryTypes.forEach(b => b.classList.remove('selected'));
                // Add to clicked
                btn.classList.add('selected');
            });
        });
    }
}
