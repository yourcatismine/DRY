class LoadingBar {
            constructor() {
                this.bar = document.getElementById('loadingBar');
                this.isLoading = false;
            }
            
            show(percentage = 0) {
                this.isLoading = true;
                this.bar.style.width = percentage + '%';
            }
            
            setProgress(percentage) {
                if (this.isLoading) {
                    this.bar.style.width = Math.min(percentage, 90) + '%';
                }
            }
            
            complete() {
                this.bar.style.width = '100%';
                setTimeout(() => {
                    this.hide();
                }, 300);
            }
            
            hide() {
                this.isLoading = false;
                this.bar.style.width = '0%';
            }
            
            startAutoLoading(duration = 3000) {
                this.show(10);
                let progress = 10;
                const interval = 50;
                const increment = (80 / (duration / interval));
                
                const timer = setInterval(() => {
                    progress += increment + Math.random() * 2;
                    this.setProgress(progress);
                    
                    if (progress >= 90) {
                        clearInterval(timer);
                        this.complete();
                    }
                }, interval);
            }
        }
        
        const loadingBar = new LoadingBar();
        
        window.addEventListener('load', () => {
            loadingBar.startAutoLoading(2000);
        });
        
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href]');
            if (link && !link.href.startsWith('#') && !link.href.startsWith('mailto:')) {
                loadingBar.show(10);
                loadingBar.startAutoLoading(1500);
            }
        });
        
        document.addEventListener('submit', () => {
            loadingBar.show(20);
            loadingBar.startAutoLoading(2000);
        });