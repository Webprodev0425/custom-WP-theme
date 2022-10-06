<div class="fl-compound-field-setting fl-animation-field-delay" style="width: 40%">
	<div class="fl-unit-field-inputs">
		<input type="number" name="{{data.name}}" value="{{data.value}}" oninput="(validity.valid)||(value='');" step="1" min="1"/>
		<div class="fl-field-unit-select">{{data.field.unit ? data.field.unit : 'days'}}</div>
	</div>
</div>
