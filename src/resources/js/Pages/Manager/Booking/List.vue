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
						<h1>Turnos</h1>
					</div>
					<div class="flex text-sm">
						<button
							class="ml-2 inline-flex items-center p-1 border border-transparent rounded-lg shadow-sm text-white bg-blue-300 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
							@click="
							form = {},
							editingBooking = false,
							open = true">
							<span>Nuevo Turno </span>
						</button>
					</div>
				</div>

				<div class="lg:flex lg:items-center lg:justify-between">
					<div class="min-w-0 flex-2 mr-2">
						<Datepicker id="date" class="w-full" v-model="search_date" :enableTimePicker="false"
							:monthChangeOnScroll="false" autoApply :format="format">
						</Datepicker>
					</div>
					<div class="min-w-0 flex-1 ml-2">
						<input class="shadow-sm text-sm border-gray-300 rounded-md" type="text" id="search"
							v-model="search" placeholder="Buscar...">
						<button
							class="ml-2 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
							@click="getBookings()">Buscar</button>
					</div>

					<div class="mt-5 flex lg:mt-0 lg:ml-4">
						<label class="font-semibold mr-2 mt-2" for="">Ver: </label>
						<select class="text-sm border-gray-300 rounded-md" v-model="length" @change="getBookings">
							<option value="2">2</option>
							<option value="5">5</option>
							<option value="10">10</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select>
					</div>
				</div>
				<!--   TABLA -->
				<div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mt-5">
					<table class="w-full whitespace-nowrap">
						<tr class="text-left font-bold bg-indigo-600 text-white">
							<th scope="col" class="py-3 px-6" @click="sort_by = 'id', sortBookings()">
								<div class="flex items-center justify-center">
									ID
									<Icons v-if="sort_by == 'id' && sort_order == 'ASC'" name="bars-up"
										class="h-4 w-4 ml-2" />
									<Icons v-else-if="sort_by == 'id' && sort_order == 'DESC'" name="bars-down"
										class="h-4 w-4 ml-2" />
									<Icons v-else name="bars" class="h-4 w-4 ml-2" />
								</div>
							</th>
							<th scope="col" class="py-3 px-6" @click="sort_by = 'date', sortBookings()">
								<div class="flex items-center justify-center">
									Fecha
									<Icons v-if="sort_by == 'date' && sort_order == 'ASC'" name="bars-up"
										class="h-4 w-4 ml-2" />
									<Icons v-else-if="sort_by == 'date' && sort_order == 'DESC'" name="bars-down"
										class="h-4 w-4 ml-2" />
									<Icons v-else name="bars" class="h-4 w-4 ml-2" />
								</div>
							</th>
							<th scope="col" class="py-3 px-6" @click="sort_by = 'fullname', sortBookings()">
								<div class="flex items-center justify-center">
									Nombre
									<Icons v-if="sort_by == 'fullname' && sort_order == 'ASC'" name="bars-up"
										class="h-4 w-4 ml-2" />
									<Icons v-else-if="sort_by == 'fullname' && sort_order == 'DESC'" name="bars-down"
										class="h-4 w-4 ml-2" />
									<Icons v-else name="bars" class="h-4 w-4 ml-2" />
								</div>
							</th>
							<th scope="col" class="py-3 px-6" @click="sort_by = 'nro_affiliate', sortBookings()">
								<div class="flex items-center justify-center">
									Nro. Afiliado
									<Icons v-if="sort_by == 'nro_affiliate' && sort_order == 'ASC'" name="bars-up"
										class="h-4 w-4 ml-2" />
									<Icons v-else-if="sort_by == 'nro_affiliate' && sort_order == 'DESC'"
										name="bars-down" class="h-4 w-4 ml-2" />
									<Icons v-else name="bars" class="h-4 w-4 ml-2" />
								</div>
							</th>
							<th scope="col" class="py-3 px-6" @click="sort_by = 'wa_id', sortBookings()">
								<div class="flex items-center justify-center">
									Telefono
									<Icons v-if="sort_by == 'wa_id' && sort_order == 'ASC'" name="bars-up"
										class="h-4 w-4 ml-2" />
									<Icons v-else-if="sort_by == 'wa_id' && sort_order == 'DESC'" name="bars-down"
										class="h-4 w-4 ml-2" />
									<Icons v-else name="bars" class="h-4 w-4 ml-2" />
								</div>
							</th>
							<th scope="col" class="py-3 px-6" @click="sort_by = 'name', sortBookings()">
								<div class="flex items-center justify-center">
									WhatsApp
									<Icons v-if="sort_by == 'name' && sort_order == 'ASC'" name="bars-up"
										class="h-4 w-4 ml-2" />
									<Icons v-else-if="sort_by == 'name' && sort_order == 'DESC'" name="bars-down"
										class="h-4 w-4 ml-2" />
									<Icons v-else name="bars" class="h-4 w-4 ml-2" />
								</div>
							</th>
							<th scope="col" class="py-3 px-6" @click="sort_by = 'status', sortBookings()">
								<div class="flex items-center justify-center">
									Estado
									<Icons v-if="sort_by == 'status' && sort_order == 'ASC'" name="bars-up"
										class="h-4 w-4 ml-2" />
									<Icons v-else-if="sort_by == 'status' && sort_order == 'DESC'" name="bars-down"
										class="h-4 w-4 ml-2" />
									<Icons v-else name="bars" class="h-4 w-4 ml-2" />
								</div>
							</th>
							<th scope="col" class="py-3 px-6">
								Accion
							</th>
						</tr>
						<tr v-for="booking in bookings.data" class="bg-white border-b text-center">
							<th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
								{{ booking.id }}
							</th>
							<td class="py-4 px-6">
								{{ booking.date }}
							</td>
							<td class="py-4 px-6">
								{{ booking.contact.fullname }}
							</td>
							<td class="py-4 px-6">
								{{ booking.contact.nro_affiliate }}
							</td>
							<td class="py-4 px-6">
								{{ booking.contact.wa_id }}
							</td>
							<td class="py-4 px-6">
								{{ booking.contact.name }}
							</td>
							<td class="py-4 px-6">
								{{ booking.status.status }}
							</td>
							<td class="py-4 px-6">
								<a type="button" @click="
								form.id = booking.id,
								form.fullname = booking.contact.fullname,
								form.name = booking.contact.name,
								form.wa_id = booking.contact.wa_id,
								form.nro_affiliate = booking.contact.nro_affiliate,
								form.status_id = booking.status.id,
								editingBooking = true,
								open = true" class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-indigo-300 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
									<Icons name="edit" class="h-5 w-5"></Icons>
								</a>
							</td>
						</tr>
					</table>
					<div class="flex justify-between mx-5 px-2 items-center p-2">
						<div>
							Mostrando: {{ this.bookings.from }} a {{ this.bookings.to }} - Entradas encontradas:
							{{ this.bookings.total }}
						</div>

						<div class="flex flex-wrap -mb-1">
							<template v-for="link in bookings.links">
								<div v-if="link.url === null"
									class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded-md"
									v-html="link.label"> </div>
								<div v-else
									class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border border-gray-300 rounded-md hover:bg-indigo-500 hover:text-white cursor-pointer"
									:class="{ 'bg-indigo-500': link.active }, { 'text-white': link.active }"
									@click="getBookingsPaginate(link.url)" v-html="link.label"> </div>
							</template>
						</div>
					</div>
				</div>

			</div>
		</template>


	</AppLayout>
	<TransitionRoot as="template" :show="open">
		<Dialog as="div" class="fixed inset-0 overflow-hidden" @close="open = false">
			<div class="absolute inset-0 overflow-hidden">
				<DialogOverlay class="absolute inset-0" />

				<div class="fixed inset-y-0 pl-16 max-w-full right-0 flex">
					<TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700"
						enter-from="translate-x-full" enter-to="translate-x-0"
						leave="transform transition ease-in-out duration-500 sm:duration-700" leave-from="translate-x-0"
						leave-to="translate-x-full">
						<div class="w-screen max-w-md">
							<form class="h-full divide-y divide-gray-200 flex flex-col bg-white shadow-xl">
								<div class="flex-1 h-0 overflow-y-auto">
									<div class="py-7 px-4 bg-gray-500 sm:px-6">
										<div class="flex items-center justify-between">
											<DialogTitle v-if="editingBooking == false"
												class="text-lg font-medium text-white">
												Nuevo Turno
											</DialogTitle>

											<DialogTitle v-else class="text-lg font-medium text-white"> Editar
												Turno </DialogTitle>
											<div class="ml-3 h-7 flex items-center">
												<button type="button"
													class="bg-gray-500 rounded-md text-indigo-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
													@click="open = false">
													<span class="sr-only">Cerrar</span>
													<Icons name="x" class="h-5 w-5"></Icons>
												</button>
											</div>
										</div>
									</div>
									<div class="flex-1 flex flex-col justify-between">
										<div class="px-4 sm:px-6 font-medium">

											<div class="mt-4">
												<label for="fullname"
													class="block text-sm font-medium text-gray-900">Nombre y
													Apellido</label>
												<div class="mt-1">
													<input type="text" v-model="form.fullname" name="fullname"
														id="fullname"
														class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 rounded-md" />
												</div>
											</div>
											<div class="mt-2">
												<label for="nro_affiliate"
													class="block text-sm font-medium text-gray-900">Nro.
													Afiliado</label>
												<div class="mt-1">
													<input type="text" v-model="form.nro_affiliate" name="nro_affiliate"
														id="nro_affiliate"
														class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 rounded-md" />
												</div>
											</div>
											<div class="mt-2">
												<label for="fullname"
													class="block text-sm font-medium text-gray-900">WhatsApp</label>
												<div class="mt-1">
													<input type="text" v-model="form.name" name="name" id="name"
														class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 rounded-md" />
												</div>
											</div>
											<div class="mt-2">
												<label for="telefono"
													class="block text-sm font-medium text-gray-900">Telefono</label>
												<div class="mt-1">
													<input type="text" v-model="form.wa_id" name="wa_id" id="wa_id"
														:disabled="editingBooking"
														:class="{ 'bg-gray-100': editingBooking }"
														class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500  rounded-md" />
												</div>
											</div>
											<div class="mt-2"  v-if="!editingBooking">
												<label for="telefono"
													class="block text-sm font-medium text-gray-900">Nro. Documento</label>
												<div class="mt-1">
													<input type="text" v-model="form.nro_doc" name="nro_doc" id="nro_doc"
														class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500  rounded-md" />
												</div>
											</div>

											<div class="mt-2"  v-if="!editingBooking">
												<label for="telefono"
													class="block text-sm font-medium text-gray-900">Fecha</label>
												<div class="mt-1">
													<Datepicker id="date" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 rounded-md bg-red-100" v-model="form.date" 
														:enableTimePicker="false" :monthChangeOnScroll="false" autoApply
														:format="format">
													</Datepicker>
												</div>
											</div>

											<div class="mt-2" v-if="editingBooking">
												<label for="telefono"
													class="block text-sm font-medium text-gray-900">Estado</label>
												<div class="mt-1">
													<select v-model="form.status_id" id="driver" name="driver"
														class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
														<option disabled value="" selected>Selecciones un Estado
														</option>
														<option v-for="status in booking_status" :key="status.id"
															:value="status.id"
															:bind:select="status.id == form.status_id">{{
																	status.status
															}}</option>
													</select>
												</div>
											</div>

										</div>
									</div>
								</div>
								<div class="flex-shrink-0 px-4 py-4 flex justify-end">
									<button type="button"
										class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
										@click="open = false">Cancelar</button>

									<button @click.prevent="storeBooking"
										class="ml-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
										Guardar
									</button>
								</div>
							</form>
						</div>
					</TransitionChild>
				</div>
			</div>
		</Dialog>
	</TransitionRoot>
</template>


<script>
import { CheckCircleIcon, ChevronRightIcon, MailIcon } from '@heroicons/vue/solid'
import AppLayout from '@/Layouts/AppLayout.vue';
import moment from 'moment';
import Icons from '@/Layouts/Components/Icons.vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import Toast from '@/Layouts/Components/Toast.vue'
import { Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

export default {
	props: {
		booking_status: Object
	},

	components: {
		AppLayout,
		CheckCircleIcon, ChevronRightIcon, MailIcon,
		moment,
		Icons,
		Datepicker,
		Toast,
		Dialog,
		DialogOverlay,
		DialogTitle,
		TransitionChild,
		TransitionRoot,
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
		return {
			bookings: "",
			length: "10",
			sort_order: 'DESC',
			sort_by: "id",
			search: "",
			search_date: "",
			loading: false,
			open: false,
			form: {},
			toastMessage: "",
			labelType: "info",
			editingUser: false
		}
	},
	watch: {

	},

	created() {
		this.getBookings()
	},
	methods: {

		clearMessage() {
			this.toastMessage = ""
		},
		async getBookings() {

			this.loading = true
			this.bookings = ""
			let filter = `&length=${this.length}`
			filter += `&sort_by=${this.sort_by}`
			filter += `&sort_order=${this.sort_order}`

			if (this.search.length > 0) {
				filter += `&search=${this.search}`
			}

			if (this.search_date > 0) {
				filter += `&search_date=${JSON.stringify(this.search_date)}`
			}

			const get = `${route('booking.list')}?${filter}`

			const response = await fetch(get, { method: 'GET' })
			this.bookings = await response.json()
			this.loading = false
		},
		async getBookingsPaginate(link) {

			var get = `${link}`;
			const response = await fetch(get, { method: 'GET' })

			this.bookings = await response.json()

		},
		sortBookings() {
			this.sort_order = this.sort_order === 'ASC' ? 'DESC' : 'ASC'
			this.getBookings()
		},
		async storeBooking() {
			let rt = ''
			if (this.editingBooking) {
				rt = route('booking.updatestatus');
			} else {
				rt = route('booking.createbooking');
			}

			axios.post(rt, {
				form: this.form,
			}).then(response => {
				this.open = false
				this.labelType = "success"
				this.toastMessage = response.data.message
				this.getBookings()
			}).catch(error => {
				this.labelType = "danger"
				this.toastMessage = error.response.data.message
			})
		},
	}
}
</script>