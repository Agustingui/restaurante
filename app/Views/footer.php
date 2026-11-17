        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url('js/reloj.js'); ?>"></script>

<?php if (session()->get('rol') == 'Mozo') { ?>
    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="avisos" data-url-listar="<?php echo base_url('mozo/avisos'); ?>" data-url-mesa="<?php echo base_url('mozo/mesa'); ?>"></div>
    <script src="<?php echo base_url('js/avisos.js'); ?>"></script>
<?php } ?>
</body>
</html>
