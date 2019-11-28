<template>
	<default-field :field="field" :errors="errors">
		<template slot="field">

			<div class="google-map" ref="map"></div>

			<div class="inputs">
				<input id="latitude" v-name="latitude_fieldname" type="hidden" v-model="latitude" />
				<input id="longitude" v-name="longitude_fieldname" type="hidden" v-model="longitude" />
			</div>
		</template>
	</default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
	mixins: [FormField, HandlesValidationErrors],

	props: ['resourceName', 'resourceId', 'field', 'placeholder'],

	data() {
		return {
			map: null,
			autocomplete: null,
			marker: null,
			latitude: null,
			longitude: null,
			placeholderText: this.placeholder
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
			formData.append(this.latitude_fieldname, this.value.latitude);
			formData.append(this.longitude_fieldname, this.value.longitude);
		},

		addMarker(lat, lng) {
			if (this.marker) {
				this.marker.setMap(null);
			}

			const coords = new google.maps.LatLng(lat, lng);

			this.marker = new google.maps.Marker({
				position: coords,
				map: this.map,
				draggable: true
			});

			this.marker.addListener('click', this.removeMarker);
			this.marker.addListener('dragend', this.updateCoords);
		},

		updateCoords(event) {
			this.value.latitude = event.latLng.lat();
			this.value.longitude = event.latLng.lng();
			this.map.setCenter(event.latLng);
		},

		removeMarker(event) {
			this.value.latitude = '';
			this.value.longitude = '';
			this.marker.setMap(null);
		}
	},

	mounted() {
		this.setInitialValue();

		const el = this.$refs['map'];
		const options = {
			zoom: this.field.zoom,
			center: new google.maps.LatLng(this.field.latitude, this.field.longitude)
		}

		this.field.fill = this.fill;

		this.map = new google.maps.Map(el, options);

        if (this.value.latitude && this.value.longitude) {
			this.addMarker(this.value.latitude, this.value.longitude);
		}

		google.maps.event.addListener(this.map, 'click', (event) => {
			this.latitude = event.latLng.lat();
			this.longitude = event.latLng.lng();

			this.addMarker(this.latitude, this.longitude);
		});

	}
}
</script>

<style scoped>
.google-map {
	width: 100%;
	height: 400px;
	margin-bottom: 10px;
}
</style>