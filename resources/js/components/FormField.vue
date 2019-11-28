<template>
	<default-field :field="field" :errors="errors" :full-width-content="true">
		<template slot="field">
			<div class="google-map" ref="map"></div>
		</template>
	</default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
	mixins: [FormField, HandlesValidationErrors],

	props: ['resourceName', 'resourceId', 'field'],

	data() {
		return {
			map: null,
			marker: null,
			latitude: null,
			longitude: null
		}
	},

	computed: {
		latitude_fieldname: function() {
			return this.field.attribute+'[latitude]';
		},
		longitude_fieldname: function() {
			return this.field.attribute+'[longitude]';
		}
	},

	methods: {
		fill(formData) {
			if (this.value.latitude && this.value.longitude) {
				console.log('fill', this.value.latitude, this.value.longitude);
				formData.append(this.latitude_fieldname, this.value.latitude);
				formData.append(this.longitude_fieldname, this.value.longitude);
			} else {
				console.log('elsefill');
			}
		},

		drawMarker() {
			if (this.marker) {
				this.marker.setMap(null);
			}

			const coords = new google.maps.LatLng(this.value.latitude, this.value.longitude);

			this.marker = new google.maps.Marker({
				position: coords,
				map: this.map,
				draggable: true
			});

			this.marker.addListener('click', this.removeMarker);
		},

		removeMarker(event) {
			this.value.latitude = null;
			this.value.longitude = null;
			this.marker.setMap(null);
		}
	},

	mounted() {
		this.setInitialValue();
		this.map = new google.maps.Map(el, options);

		const centerLat = this.value.latitude ? this.value.latitude : this.field.latitude;
		const centerLng = this.value.longitude ? this.value.longitude : this.field.longitude;

		const el = this.$refs['map'];
		const options = {
			zoom: this.field.zoom,
			center: new google.maps.LatLng(centerLat, centerLng)
		}


        if (this.value.latitude && this.value.longitude) {
			this.drawMarker();
		}

		console.log(this.value);
		google.maps.event.addListener(this.map, 'click', (event) => {
			this.value.latitude = event.latLng.lat();
			this.value.longitude = event.latLng.lng();

			this.drawMarker();
		});
	}
}
</script>

<style scoped>
.google-map {
	width: 100%;
	height: 400px;
	margin-bottom: 20px;
}
</style>