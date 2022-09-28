
@if (isset($side))
<div class="row g-3 text-center mt-5">
    <div>
        <button type="submit" id="btnGuardar" class="btn btn-success">Guardar</button>
        <input class="btn btn-danger" type="reset" value="Deshacer">
        </div>
    </div>
@else
<div class="row g-3 text-center mt-5">
<x-cards>    
    
<div class="text-center">
            <button type="submit" id="btnGuardar" class="btn btn-sm btn-success btn-icon-split"><span class="icon text-white-50">
                            <i class="fas fa-save"></i>
                        </span>
                        <span class="text">Guardar</span></button>
            <input class="btn btn-sm btn-danger" type="reset" value="Deshacer">
        </div>
    </x-cards>
    </div>
        
    @endif
        