</div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <!-- Custom JS -->
    <script src="<?= BASE_URL ?>assets/js/script.js"></script>
    
    <script>
        // Inicializar DataTables solo si no se ha hecho ya
        $(document).ready(function() {
            // Evitamos inicializar DataTables aquí para evitar duplicación
            // La inicialización se hace en script.js
            
            // Auto-cerrar alertas después de 5 segundos
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
            
            // Toggle del sidebar
            $("#menu-toggle").click(function(e) {
                e.preventDefault();
                $("#wrapper").toggleClass("toggled");
            });
        });
    </script>
</body>
</html>