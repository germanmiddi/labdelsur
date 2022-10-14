<template>
	<AppLayout>
		<template #content>
			<Toast :toast="this.toastMessage" :type="this.labelType" @clear="clearMessage"></Toast>

			<div class="focus-within:content flex-grow flex flex-col">
				<div class="mx-auto my-10 text-4xl font-bold
							xl: w-11/12
							lg: w-11/12
							flex justify-between">

					<div class="flex items-center">
						<h1>Configuraciones</h1>
					</div>
				</div>

				<div class="lg:flex lg:items-center lg:justify-between">

				</div>
				<!--   TABLA -->
				<div class="grid grid-cols-2 gap-2">
					<div class=" col-span-1">
						<h1 class="text-center font-semibold text-2xl">Configuración por Dia</h1>
						<div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mt-5">

							<table class="w-full whitespace-nowrap">
								<tr class="text-left font-bold bg-indigo-600 text-white">
									<th scope="col" class="py-3 px-6">
										Dia
									</th>
									<th scope="col" class="py-3 px-6">
										Turnos
									</th>
									<th scope="col" class="py-3 px-6">
										Acción
									</th>
								</tr>
								<tr v-for="day in list_days"
									class="bg-white border-b dark:bg-gray-900 dark:border-gray-700 text-center hover:bg-gray-100 focus-within:bg-gray-100">
									<th scope="row"
										class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
										{{day.description}}
									</th>
									<td class="py-4 px-6">
										{{day.cant_orders}}
									</td>
									<td class="py-4 px-6">
										<button @click="editDay = true,
										form_day.id=day.id,
										form_day.num_day=day.num_day,
										form_day.description=day.description,
										form_day.cant_orders=day.cant_orders"
											class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-blue-300 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
											<Icons name="edit" class="h-5 w-5"></Icons>
										</button>
									</td>
								</tr>
							</table>
							<form v-show="editDay" action="#" method="POST">
								<div class="shadow overflow-hidden sm:rounded-md mt-2">
									<div class="px-4 py-5 bg-white sm:p-6">
										<div class="grid grid-cols-6 gap-6">
											<div class="col-span-6 sm:col-span-6">
												<label for="google_area1"
													class="block text-sm font-medium text-gray-700">Dia</label>
												<input type="text" name="description" id="description"
													v-model="form_day.description"
													class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-50"
													disabled />
											</div>
											<div class="col-span-6 sm:col-span-6">
												<label for="google_postal_code"
													class="block text-sm font-medium text-gray-700">Turnos
												</label>
												<input type="number" name="cant_orders" id="cant_orders"
													v-model="form_day.cant_orders"
													class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
											</div>

											<div class="col-span-6 sm:col-span-6">
												<a type="button" @click="updateDay()"
													class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Actualizar
												</a>
												<a type="button" @click="editDay = false"
													class="ml-2 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Cancelar
												</a>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>

					<div class=" col-span-1">
						<h1 class="text-center font-semibold text-2xl">Listado de Feriados</h1>
						<div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mt-5">
							<table class="w-full whitespace-nowrap">
								<tr class="text-left font-bold bg-indigo-600 text-white">
									<th scope="col" class="py-3 px-6">
										Fecha
									</th>
									<th scope="col" class="py-3 px-6">
										Detalle
									</th>
									<th scope="col" class="py-3 px-6">
										Acción
									</th>
								</tr>
								<tr v-for="holiday in list_holidays"
									class="bg-white border-b dark:bg-gray-900 dark:border-gray-700 text-center hover:bg-gray-100 focus-within:bg-gray-100">
									<th scope="row"
										class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
										{{format(holiday.date)}}

									</th>
									<td class="py-4 px-6">
										{{holiday.description}}
									</td>
									<td class="py-4 px-6">
										<a type="button" @click="deleteHoliday(holiday.id)"
											class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-blue-300 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
											<Icons name="trash" class="h-5 w-5"></Icons>
										</a>
									</td>
								</tr>
							</table>
							<div class="mt-6 flex flex-col justify-stretch">
								<a type="button" @click="newHoliday = true" v-show="!newHoliday"
									class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Nuevo
									Feriado</a>
							</div>

							<form v-show="newHoliday" action="#" method="POST">
								<div class="shadow overflow-hidden sm:rounded-md mt-2 ">
									<div class="px-4 py-5 bg-white sm:p-6">
										<div class="grid grid-cols-6 gap-6">

											<div class="col-span-6 sm:col-span-6">
												<label for="google_area1"
													class="block text-sm font-medium text-gray-700">Fecha</label>
												<Datepicker id="date" class="w-full mt-1" v-model="form_holiday.date"
													:enableTimePicker="false" :monthChangeOnScroll="false" autoApply
													:format="format">
												</Datepicker>
											</div>

											<div class="col-span-6 sm:col-span-6">
												<label for="google_postal_code"
													class="block text-sm font-medium text-gray-700">Descripcion
												</label>
												<input type="text" name="description" id="description"
													v-model="form_holiday.description"
													class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
											</div>

											<div class="col-span-6 sm:col-span-6">
												<a type="button" @click="storeHoliday()"
													class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Guardar
												</a>
												<a type="button" @click="newHoliday = false"
													class="ml-2 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Cancelar
												</a>
											</div>

										</div>
									</div>
								</div>
							</form>
						</div>
					</div>

					<div class=" col-span-1">
						<h1 class="text-center font-semibold text-2xl">Configuración de Turnos</h1>
						<div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mt-5">
							<form action="#" method="POST">

								<div class="px-4 py-5 bg-white sm:p-6">
									<div class="grid grid-cols-6 gap-6">
										<div class="col-span-6 sm:col-span-6">
											<label for="google_area1"
												class="block text-sm font-medium text-gray-700">Cantidad de dias</label>
											<input type="number" name="cant_days_booking" id="cant_days_booking"
												v-model="cant_days_booking.value"
												class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
										</div>
										<div class="col-span-6 sm:col-span-6">
											<label for="google_postal_code"
												class="block text-sm font-medium text-gray-700">Horario de corte.
											</label>
											<Datepicker id="hora_limit_booking" name="hora_limit_booking"
												class="w-full mt-1" v-model="hora_limit_booking.value"
												:startTime="startTime" timePicker>
											</Datepicker>
										</div>
										<div class="col-span-6 sm:col-span-6">
											<label for="google_postal_code"
												class="block text-sm font-medium text-gray-700">Fecha de último turno
												disponible.
											</label>
											<Datepicker id="day_limit_booking" class="w-full mt-1"
												v-model="day_limit_booking.value" name="day_limit_booking"
												:enableTimePicker="false" :monthChangeOnScroll="false" autoApply
												:format="format">
											</Datepicker>
										</div>

										<div class="col-span-6 sm:col-span-6">
											<a type="button" @click="updateSetting"
												class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Guardar
											</a>
										</div>

									</div>
								</div>
							</form>
						</div>
					</div>

				</div>
			</div>
		</template>
	</AppLayout>
</template>


<script>
import { CheckCircleIcon, ChevronRightIcon, MailIcon } from '@heroicons/vue/solid'
import AppLayout from '@/Layouts/AppLayout.vue';
import moment from 'moment';
import Icons from '@/Layouts/Components/Icons.vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import Toast from '@/Layouts/Components/Toast.vue'

export default {
	props: {
		holidays: Object,
		days: Object,
		settings: Object
	},

	components: {
		AppLayout,
		CheckCircleIcon, ChevronRightIcon, MailIcon,
		moment,
		Icons,
		Datepicker,
		Toast
	},
	setup() {
		const format = (date) => {
			const day = date.getDate();
			const month = date.getMonth() + 1;
			const year = date.getFullYear();

			return `${day}/${month}/${year}`;
		}
		return {
			format
		}
	},
	data() {
		var list_settings = this.settings

		var cant_days_booking = list_settings.filter(obj => obj.key == 'cant_days_booking')
		var hora_limit_booking = list_settings.filter(obj => obj.key == 'hora_limit_booking')
		var day_limit_booking = list_settings.filter(obj => obj.key == 'day_limit_booking')
		return {
			cant_days_booking: cant_days_booking[0],
			hora_limit_booking: hora_limit_booking[0],
			day_limit_booking: day_limit_booking[0],
			newHoliday: false,
			editDay: false,
			form_day: {},
			form_holiday: {},
			list_days: this.days,
			list_holidays: this.holidays,
			toastMessage: "",
			labelType: "info",
		}
	},
	watch: {

	},

	created() {
		this.list_day = this.days
		this.list_holiday = this.holidays
		this.formatHora();
	},
	methods: {

		clearMessage() {
			this.toastMessage = ""
		},
		format(date) {
			return moment(date).format('DD-MM-YYYY');
		},

		formatHora() {
			this.hora_limit_booking.value = { hours: moment(this.hora_limit_booking.value, 'HH:mm:ss').format('H'), minutes: moment(this.hora_limit_booking.value, 'HH:mm:ss').format('mm') };
		},
		updateDay() {
			this.editDay = false

			let rt = route('settings.updateday');

			axios.post(rt, {
				form: this.form_day,
			}).then(response => {
				this.labelType = "success"
				this.toastMessage = response.data.message
				this.listDay()
			}).catch(error => {
				this.labelType = "danger"
				this.toastMessage = error.response.data.message
			})
		},
		storeHoliday() {
			this.newHoliday = false

			let rt = route('settings.storeholiday');

			axios.post(rt, {
				form: this.form_holiday,
			}).then(response => {
				this.labelType = "success"
				this.toastMessage = response.data.message
				this.listHoliday()
			}).catch(error => {
				this.labelType = "danger"
				this.toastMessage = error.response.data.message
			})
		},
		deleteHoliday(id) {
			let rt = route('settings.deleteholiday', id);
			axios.get(rt).then(response => {
				this.labelType = "success"
				this.toastMessage = response.data.message
				this.listHoliday()
			}).catch(error => {
				this.labelType = "danger"
				this.toastMessage = error.response.data.message
			})
		},
		async listHoliday() {
			const get = `${route('settings.listholiday')}`

			const response = await fetch(get, { method: 'GET' })
			this.list_holidays = await response.json()
		},
		async listDay() {
			const get = `${route('settings.listday')}`

			const response = await fetch(get, { method: 'GET' })
			this.list_days = await response.json()
		},
		updateSetting() {
			var rows = []
			rows.push({
				id: this.cant_days_booking.id,
				value: this.cant_days_booking.value
			})

			rows.push({
				id: this.hora_limit_booking.id,
				value: this.hora_limit_booking.value.hours + ':' + this.hora_limit_booking.value.minutes + ':00'
			})

			rows.push({
				id: this.day_limit_booking.id,
				value: this.day_limit_booking.value
			})

			let myJsonString = JSON.stringify(rows);
			let post = route('settings.update', myJsonString)

			axios.post(post)
				.then(response => {
					this.labelType = "success"
					this.toastMessage = response.data.message
				}).catch(error => {
					this.labelType = "danger"
					this.toastMessage = error.response.data.message
				})
		}
	}
}
</script>