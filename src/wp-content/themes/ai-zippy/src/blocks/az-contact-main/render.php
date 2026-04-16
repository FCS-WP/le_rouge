<?php
$formTitle = $attributes['formTitle'] ?? '';
$formSubtitle = $attributes['formSubtitle'] ?? '';
?>
<div <?php echo get_block_wrapper_attributes(['class' => 'contact-body-wrapper']); ?>>
    <section class="contact-body">
        <div class="contact-locations">
            <!-- Location 1 -->
            <div class="location-card">
                <span class="location-num serif">01</span>
                <h2 class="location-name serif">Raffles Place</h2>
                <div class="location-detail">
                    <span class="location-detail-icon">◇</span>
                    <span class="location-detail-text">1 Raffles Place, #B1-K1 OUB Centre, Singapore 048616</span>
                </div>
                <div class="location-detail">
                    <span class="location-detail-icon">◇</span>
                    <span class="location-detail-text">+65 6532 7992</span>
                </div>
                <div class="location-detail">
                    <span class="location-detail-icon">◇</span>
                    <span class="location-detail-text">rafflesplace@lerougesg.com</span>
                </div>
                <div class="location-hours">
                    <h5>Opening Hours</h5>
                    <div class="hours-row"><span>Mon – Fri</span><span>10:00 AM – 8:00 PM</span></div>
                    <div class="hours-row"><span>Saturday</span><span>11:00 AM – 7:00 PM</span></div>
                    <div class="hours-row"><span>Sunday & PH</span><span>Closed</span></div>
                </div>
                <div style="margin-top:24px;">
                    <a href="#" class="btn-primary">Get Directions</a>
                </div>
            </div>
            <!-- Location 2 -->
            <div class="location-card">
                <span class="location-num serif">02</span>
                <h2 class="location-name serif">Marina Bay Link Mall</h2>
                <div class="location-detail">
                    <span class="location-detail-icon">◇</span>
                    <span class="location-detail-text">8A Marina Blvd, #B2-44 Singapore 018984</span>
                </div>
                <div class="location-detail">
                    <span class="location-detail-icon">◇</span>
                    <span class="location-detail-text">+65 6509 1871</span>
                </div>
                <div class="location-detail">
                    <span class="location-detail-icon">◇</span>
                    <span class="location-detail-text">mblm@lerougesg.com</span>
                </div>
                <div class="location-hours">
                    <h5>Opening Hours</h5>
                    <div class="hours-row"><span>Mon – Fri</span><span>10:00 AM – 8:00 PM</span></div>
                    <div class="hours-row"><span>Saturday</span><span>11:00 AM – 7:00 PM</span></div>
                    <div class="hours-row"><span>Sunday & PH</span><span>Closed</span></div>
                </div>
                <div style="margin-top:24px;">
                    <a href="#" class="btn-primary">Get Directions</a>
                </div>
            </div>
            <!-- Location 3 -->
            <div class="location-card">
                <span class="location-num serif">03</span>
                <h2 class="location-name serif">Republic Plaza</h2>
                <div class="location-detail">
                    <span class="location-detail-icon">◇</span>
                    <span class="location-detail-text">9 Raffles Place, #01-07 Singapore 048619</span>
                </div>
                <div class="location-detail">
                    <span class="location-detail-icon">◇</span>
                    <span class="location-detail-text">+65 6226 1944</span>
                </div>
                <div class="location-detail">
                    <span class="location-detail-icon">◇</span>
                    <span class="location-detail-text">republicplaza@lerougesg.com</span>
                </div>
                <div class="location-hours">
                    <h5>Opening Hours</h5>
                    <div class="hours-row"><span>Mon – Fri</span><span>10:00 AM – 8:00 PM</span></div>
                    <div class="hours-row"><span>Saturday</span><span>11:00 AM – 7:00 PM</span></div>
                    <div class="hours-row"><span>Sunday & PH</span><span>Closed</span></div>
                </div>
                <div style="margin-top:24px;">
                    <a href="#" class="btn-primary">Get Directions</a>
                </div>
            </div>
        </div>

        <div class="contact-form-container">
            <h2 class="form-title serif"><?php echo wp_kses_post($formTitle); ?></h2>
            <p class="form-sub"><?php echo wp_kses_post($formSubtitle); ?></p>
            
            <form class="az-contact-form">
                <p class="eyebrow" style="margin-bottom:14px;">Enquiry Type</p>
                <div class="enquiry-types">
                    <div class="enquiry-type selected">General Enquiry</div>
                    <div class="enquiry-type">Bulk Orders</div>
                    <div class="enquiry-type">Concierge</div>
                    <div class="enquiry-type">Product Question</div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-input" placeholder="John">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-input" placeholder="Tan">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-input" placeholder="john@example.com">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" class="form-input" placeholder="+65 9XXX XXXX">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Preferred Location</label>
                    <select class="form-input form-select">
                        <option value="">Select a location</option>
                        <option>Raffles Place</option>
                        <option>Marina Bay Link Mall</option>
                        <option>Republic Plaza</option>
                        <option>Online / Delivery</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Message</label>
                    <textarea class="form-textarea form-input" placeholder="Tell us how we can help..."></textarea>
                </div>
                
                <button type="submit" class="btn-primary" style="width:100%; cursor:pointer;">Send Message</button>
                <p style="font-size:11px; color:var(--text-dim); margin-top:16px; text-align:center;">We typically respond within 1 business day.</p>
            </form>
        </div>
    </section>
</div>
