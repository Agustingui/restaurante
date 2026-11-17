<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
        <h3 class="mb-0 d-flex align-items-center gap-2"><span class="material-symbols-outlined">restaurant</span>Comandas en cocina</h3>

        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="chip"><span class="punto punto-gris"></span> Pendientes <strong id="n-pend">0</strong></span>
            <span class="chip"><span class="punto punto-ambar"></span> En preparación <strong id="n-prep">0</strong></span>
            <span class="chip"><span class="punto punto-rojo"></span> Demoradas <strong id="n-dem">0</strong></span>
            <span id="mensaje" class="text-muted small"></span>
        </div>
    </div>

    <p class="text-muted small mb-3">
        Teclas: 1 al 9 seleccionar · Flechas moverse · Enter confirmar · Esc cancelar
    </p>

    <div class="row g-3" id="comandas" data-url-listar="<?php echo base_url('cocina/listar'); ?>" data-url-estado="<?php echo base_url('cocina/cambiarestado'); ?>"></div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?php echo base_url('js/cocina.js'); ?>"></script>
