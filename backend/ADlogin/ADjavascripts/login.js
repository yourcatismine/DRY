document.addEventListener('DOMContentLoaded', function() {
            const errorMessage = document.querySelector('.message-error');
            
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.classList.add('show');
                }, 100);
                
                setTimeout(() => {
                    errorMessage.classList.add('fade-out');
                    errorMessage.classList.remove('show');
                    
                    setTimeout(() => {
                        errorMessage.style.display = 'none';
                    }, 500);
                }, 3000);
                
                errorMessage.addEventListener('click', function(e) {
                    if (e.target === errorMessage || e.target.classList.contains('error-message')) {
                        const rect = e.target.getBoundingClientRect();
                        const clickX = e.clientX - rect.left;
                        const clickY = e.clientY - rect.top;
                        
                        if (clickX > rect.width - 30 && clickY < 30) {
                            errorMessage.classList.add('fade-out');
                            errorMessage.classList.remove('show');
                            
                            setTimeout(() => {
                                errorMessage.style.display = 'none';
                            }, 500);
                        }
                    }
                });
            }
        });