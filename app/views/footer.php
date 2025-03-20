    <footer class="max-w-7xl mx-auto text-center text-gray-600 md:p-12">
        <p class="text-sm">&copy; <?php echo date('Y'); ?> Product Manager. All rights reserved.</p>
        <p class="text-sm mt-2">Designed with <span class="text-red-500">❤️</span> by Duy</p>
    </footer>

    <!-- Animation Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const elements = document.querySelectorAll('.animate-fade-in');
            elements.forEach((el, index) => {
                el.style.animation = `fadeIn 0.5s ease-in-out ${index * 0.1}s forwards`;
            });
        });
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { opacity: 0; }
    </style>
</body>
</html>
