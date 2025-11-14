@extends('master')

@section('title', 'Test KHQR Generation — Sample Store')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h3 class="mb-0 text-center">
                        <i class="fas fa-qrcode mr-2 text-success"></i>
                        Test KHQR Generation
                    </h3>
                </div>
                
                <div class="card-body">
                    <!-- QR String Input Form -->
                    <form id="qrForm" class="mb-4">
                        <div class="form-group">
                            <label for="qrString" class="font-weight-bold">KHQR String:</label>
                            <textarea 
                                id="qrString" 
                                class="form-control" 
                                rows="3" 
                                placeholder="Enter KHQR string here..."
                            >00020101021229210017premprey_kim@trmc520459995303116540450005802KH5910Jonh Smith6010PHNOM PENH991700131760927985466630418F9</textarea>
                            <small class="form-text text-muted">
                                Default string is pre-filled. You can modify it or paste a new one.
                            </small>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-success mr-2">
                                <i class="fas fa-qrcode mr-2"></i>Generate QR Code
                            </button>
                            <button type="button" id="resetBtn" class="btn btn-outline-secondary">
                                <i class="fas fa-undo mr-2"></i>Reset
                            </button>
                        </div>
                    </form>

                    <!-- Loading State -->
                    <div id="loading" class="text-center" style="display: none;">
                        <div class="spinner-border text-success mb-3" role="status">
                            <span class="sr-only">Generating...</span>
                        </div>
                        <p class="text-muted">Generating QR code...</p>
                    </div>

                    <!-- QR Code Display -->
                    <div id="qrResult" class="text-center" style="display: none;">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h5 class="card-title text-success">
                                    <i class="fas fa-check-circle mr-2"></i>QR Code Generated Successfully
                                </h5>
                                
                                <!-- QR Code Image -->
                                <div class="mb-3">
                                    <img id="qrImage" src="" alt="Generated QR Code" class="img-fluid border rounded shadow-sm" style="max-width: 300px;">
                                </div>
                                
                                <!-- QR String Display -->
                                <div class="mb-3">
                                    <h6 class="text-muted">KHQR String:</h6>
                                    <div class="bg-white p-3 rounded border">
                                        <code id="qrStringDisplay" style="font-size: 12px; word-break: break-all;"></code>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div>
                                    <button type="button" id="downloadBtn" class="btn btn-primary mr-2">
                                        <i class="fas fa-download mr-2"></i>Download QR
                                    </button>
                                    <a id="viewQRPageBtn" href="#" class="btn btn-success mr-2" target="_blank">
                                        <i class="fas fa-external-link-alt mr-2"></i>View Payment Page
                                    </a>
                                    <button type="button" id="copyBtn" class="btn btn-outline-dark">
                                        <i class="fas fa-copy mr-2"></i>Copy String
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error Display -->
                    <div id="errorResult" class="text-center" style="display: none;">
                        <div class="alert alert-danger">
                            <h5 class="alert-heading">
                                <i class="fas fa-exclamation-triangle mr-2"></i>Error
                            </h5>
                            <p id="errorMessage" class="mb-0"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Card -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-info-circle text-info mr-2"></i>About KHQR
                    </h5>
                    <p class="card-text">
                        KHQR (Khmer QR) is Cambodia's national QR code standard for payments. 
                        The string format follows the EMVCo specification and contains information 
                        about the merchant, amount, and payment details.
                    </p>
                    
                    <h6 class="mt-3">String Format Breakdown:</h6>
                    <ul class="small">
                        <li><strong>00020101</strong> - Format identifier</li>
                        <li><strong>0212</strong> - Payment network identifier</li>
                        <li><strong>29210017premprey_kim@trmc</strong> - Merchant account info</li>
                        <li><strong>5204</strong> - Merchant category code</li>
                        <li><strong>5999</strong> - Category (Other services)</li>
                        <li><strong>5303116</strong> - Currency code (116 = USD)</li>
                        <li><strong>5404</strong> - Transaction amount</li>
                        <li><strong>5802KH</strong> - Country code</li>
                        <li><strong>5910Jonh Smith</strong> - Merchant name</li>
                        <li><strong>6010PHNOM PENH</strong> - Merchant city</li>
                        <li><strong>6304</strong> - CRC checksum</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const qrForm = document.getElementById('qrForm');
    const qrString = document.getElementById('qrString');
    const loading = document.getElementById('loading');
    const qrResult = document.getElementById('qrResult');
    const errorResult = document.getElementById('errorResult');
    const qrImage = document.getElementById('qrImage');
    const qrStringDisplay = document.getElementById('qrStringDisplay');
    const downloadBtn = document.getElementById('downloadBtn');
    const copyBtn = document.getElementById('copyBtn');
    const resetBtn = document.getElementById('resetBtn');
    const viewQRPageBtn = document.getElementById('viewQRPageBtn');

    let currentQRData = null;

    qrForm.addEventListener('submit', function(e) {
        e.preventDefault();
        generateQR();
    });

    resetBtn.addEventListener('click', function() {
        qrString.value = '00020101021229210017premprey_kim@trmc520459995303116540450005802KH5910Jonh Smith6010PHNOM PENH991700131760927985466630418F9';
        hideResults();
    });

    copyBtn.addEventListener('click', function() {
        navigator.clipboard.writeText(qrString.value).then(function() {
            // Show success feedback
            const originalText = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
            copyBtn.classList.remove('btn-outline-dark');
            copyBtn.classList.add('btn-success');
            
            setTimeout(function() {
                copyBtn.innerHTML = originalText;
                copyBtn.classList.remove('btn-success');
                copyBtn.classList.add('btn-outline-dark');
            }, 2000);
        });
    });

    downloadBtn.addEventListener('click', function() {
        if (currentQRData && currentQRData.qr_code) {
            downloadQRCode(currentQRData.qr_code);
        }
    });

    function generateQR() {
        const qrStringValue = qrString.value.trim();
        
        if (!qrStringValue) {
            showError('Please enter a KHQR string');
            return;
        }

        hideResults();
        loading.style.display = 'block';

        // Use fetch to call the API
        fetch('/khqr/generate-from-string', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                qr_string: qrStringValue
            })
        })
        .then(response => response.json())
        .then(data => {
            loading.style.display = 'none';
            
            if (data.success) {
                showQRResult(data.qr_code, qrStringValue);
            } else {
                showError(data.error || 'Failed to generate QR code');
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            console.error('Error:', error);
            showError('Network error occurred');
        });
    }

    function showQRResult(qrCodeData, qrStringValue) {
        currentQRData = qrCodeData;
        
        if (qrCodeData.qr_code) {
            qrImage.src = 'data:image/png;base64,' + qrCodeData.qr_code;
        } else if (qrCodeData.qr_url) {
            qrImage.src = qrCodeData.qr_url;
        }
        
        qrStringDisplay.textContent = qrStringValue;
        
        // Set up the view payment page link
        const customQRUrl = '/khqr/custom-qr?qr_string=' + encodeURIComponent(qrStringValue);
        viewQRPageBtn.href = customQRUrl;
        
        qrResult.style.display = 'block';
    }

    function showError(message) {
        document.getElementById('errorMessage').textContent = message;
        errorResult.style.display = 'block';
    }

    function hideResults() {
        qrResult.style.display = 'none';
        errorResult.style.display = 'none';
        loading.style.display = 'none';
    }

    function downloadQRCode(base64Data) {
        const link = document.createElement('a');
        link.download = 'khqr-code.png';
        link.href = 'data:image/png;base64,' + base64Data;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
});
</script>
@endpush

@push('styles')
<style>
.card {
    border-radius: 15px;
}
.card-header {
    border-radius: 15px 15px 0 0 !important;
}
.btn {
    border-radius: 8px;
    font-weight: 600;
}
code {
    background: #f8f9fa;
    padding: 8px;
    border-radius: 5px;
    display: inline-block;
    max-width: 100%;
}
textarea {
    font-family: 'Courier New', monospace;
    font-size: 14px;
}
</style>
@endpush