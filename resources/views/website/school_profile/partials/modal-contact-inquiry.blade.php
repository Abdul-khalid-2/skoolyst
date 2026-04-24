<div id="contactInquiryForm" class="modal" role="dialog" aria-modal="true" aria-labelledby="contactInquiryTitle" aria-hidden="true">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="contactInquiryTitle">Submit Query</h3>
            <span class="close" role="button" tabindex="0" aria-label="Close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="login-required">
                <div class="login-icon">
                    <i class="fas fa-user-lock"></i>
                </div>
                <h4>Login Required</h4>
                <p>Please login to submit your query and help other parents make informed decisions.</p>
                <div class="auth-buttons">
                    <a href="{{ route('login') }}" class="btn-login">Login</a>
                    <a href="{{ route('register') }}" class="btn-register">Register</a>
                </div>
            </div>
        </div>
    </div>
</div>
