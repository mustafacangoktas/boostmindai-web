<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebarBurger = document.querySelector('.sidebar-burger');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        const sidebarClose = document.querySelector('.sidebar-close');

        if (sidebarBurger && sidebar && overlay) {
            sidebarBurger.addEventListener('click', function () {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
                document.body.classList.toggle('sidebar-open');
            });

            overlay.addEventListener('click', function () {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.classList.remove('sidebar-open');
            });

            if (sidebarClose) {
                sidebarClose.addEventListener('click', function () {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    document.body.classList.remove('sidebar-open');
                });
            }
        }

        const dropdownButtons = document.querySelectorAll('.sidebar__chat-menu');

        dropdownButtons.forEach(button => {
            button.addEventListener('show.bs.dropdown', function () {
                this.closest('.sidebar__chat-item').classList.add('dropdown-active');
            });

            button.addEventListener('hidden.bs.dropdown', function () {
                this.closest('.sidebar__chat-item').classList.remove('dropdown-active');
            });
        });

        const textarea = document.querySelector('.chat-textarea');
        if (!textarea) return;

        function autoResize() {
            // max height is set to 140px, adjust as needed
            const maxHeight = 140;
            const minHeight = 50; // Minimum height to prevent collapsing
            textarea.style.minHeight = minHeight + 'px';
            textarea.style.height = 'auto'; // Reset height to auto to get the scrollHeight correctly
            textarea.style.height = Math.min(textarea.scrollHeight, maxHeight) + 'px';
            // Ensure the textarea doesn't exceed max height
            if (textarea.scrollHeight > maxHeight) {
                textarea.style.overflowY = 'auto'; // Enable scroll if content exceeds max height
            } else {
                textarea.style.overflowY = 'hidden'; // Hide scroll if content fits
            }
        }

        textarea.addEventListener('input', autoResize);
    });
</script>

