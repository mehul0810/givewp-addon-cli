document.addEventListener( 'DOMContentLoaded', () => {
	const activateBtn = document.getElementById( 'mgffw-activate-btn' );
	const deactivateBtn = document.getElementById( 'mgffw-deactivate-btn' );
	const licenseKey = document.getElementById( 'mgffw-license-key' );

	if ( null !== licenseKey ) {
		licenseKey.addEventListener( 'keyup', e => {
			if ( '' !== e.target.value ) {
				activateBtn.removeAttribute( 'disabled' );
			} else {
				activateBtn.setAttribute( 'disabled', 'disabled' );
			}
		} );
	}

	if ( null !== activateBtn ) {
		activateBtn.addEventListener( 'click', e => {
			e.preventDefault();

			activateBtn.value = activateBtn.getAttribute( 'data-processing-text' );

			const formData = new FormData();
			formData.append( 'action', 'mgffw_activate_license' );
			formData.append( 'license_key', null !== licenseKey ? licenseKey.value : '' );

			fetch(
				ajaxurl,
				{
					method: 'POST',
					body: formData,
				}
			).then( response => {
				if ( 200 === response.status ) {
					return response.json();
				}

				return false;
			} ).then( response => {
				if ( response.success ) {
					activateBtn.removeAttribute( 'disabled' );
					activateBtn.value = activateBtn.getAttribute( 'data-default-text' );
					activateBtn.classList.add( 'mgffw-hidden' );
					deactivateBtn.classList.remove( 'mgffw-hidden' );
					licenseKey.style.border = 'none';
				} else {
					licenseKey.style.border = '1px solid red';
				}
			} );
		} );
	}

	if ( null !== deactivateBtn ) {
		deactivateBtn.addEventListener( 'click', e => {
			e.preventDefault();

			deactivateBtn.value = deactivateBtn.getAttribute( 'data-processing-text' );

			const formData = new FormData();
			formData.append( 'action', 'mgffw_deactivate_license' );
			formData.append( 'license_key', null !== licenseKey ? licenseKey.value : '' );

			fetch(
				ajaxurl,
				{
					method: 'POST',
					body: formData,
				}
			).then( response => {
				if ( 200 === response.status ) {
					return response.json();
				}

				return false;
			} ).then( response => {
				if ( response.success ) {
					deactivateBtn.value = deactivateBtn.getAttribute( 'data-default-text' );
					deactivateBtn.classList.add( 'mgffw-hidden' );
					licenseKey.value = '';
					activateBtn.classList.remove( 'mgffw-hidden' );
					licenseKey.style.border = 'none';
				} else {
					licenseKey.style.border = '1px solid red';
				}
			} );
		} );
	}
} );
