class GitHubTypewriter {
            constructor() {
                this.typewriterEl = document.getElementById("typewriter");
                this.commandEl = document.getElementById("command");
                this.cursorEl = document.getElementById("cursor");
                this.typeDelay = 120;
                this.backspaceDelay = 60;
                this.pauseAfterType = 4000;
                this.pauseAfterBackspace = 1200;
                
                this.commands = [
                    {
                        cmd: "clone",
                        text: "welcome-to-dry.git",
                        className: "cmd-clone"
                    },
                    {
                        cmd: "push",
                        text: "origin main --force-with-lease",
                        className: "cmd-push"
                    },
                    {
                        cmd: "pull",
                        text: "upstream develop --rebase",
                        className: "cmd-pull"
                    },
                    {
                        cmd: "github",
                        text: "github.com/yourcatismine",
                        className: "cmd-clone"
                    },
                    {
                        cmd: "push",
                        text: "origin feature/new-awesome-feature",
                        className: "cmd-push"
                    }
                ];
                
                this.currentIndex = 0;
                this.isTyping = false;
                this.isBackspacing = false;
            }

            async typeText(text) {
                this.isTyping = true;
                this.typewriterEl.innerHTML = "";
                
                for (let i = 0; i < text.length; i++) {
                    const span = document.createElement("span");
                    span.className = "typing-letter";
                    span.textContent = text[i];
                    this.typewriterEl.appendChild(span);
                    
                    await this.delay(this.typeDelay);
                    span.classList.add("visible");
                }
                
                this.isTyping = false;
            }

            async backspaceText() {
                this.isBackspacing = true;
                const letters = this.typewriterEl.querySelectorAll(".typing-letter");
                
                for (let i = letters.length - 1; i >= 0; i--) {
                    letters[i].classList.add("backspacing");
                    await this.delay(this.backspaceDelay);
                    letters[i].remove();
                }
                
                this.isBackspacing = false;
            }

            updateCommand(command) {
                this.commandEl.textContent = command.cmd;
                this.commandEl.className = command.className;
            }

            delay(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            }

            async startCycle() {
                while (true) {
                    const currentCommand = this.commands[this.currentIndex];
                    
                    this.updateCommand(currentCommand);
                    await this.typeText(currentCommand.text);
                    await this.delay(this.pauseAfterType);
                    await this.backspaceText();
                    await this.delay(this.pauseAfterBackspace);
                    
                    this.currentIndex = (this.currentIndex + 1) % this.commands.length;
                }
            }

            init() {
                this.startCycle();
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            const typewriter = new GitHubTypewriter();
            typewriter.init();
        });

        document.addEventListener("DOMContentLoaded", () => {
            const dots = document.querySelectorAll('.dot');
            dots.forEach(dot => {
                dot.addEventListener('mouseenter', () => {
                    dot.style.transform = 'scale(1.3)';
                });
                dot.addEventListener('mouseleave', () => {
                    dot.style.transform = 'scale(1)';
                });
            });
        });