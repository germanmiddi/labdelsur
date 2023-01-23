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
						<h1>Obras Sociales</h1>
					</div>
					<div class="flex text-sm">
						<button
							class="ml-2 inline-flex items-center p-1 border border-transparent rounded-lg shadow-sm text-white bg-blue-300 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
							@click="
							form = {},
							editingObrasSociales = false,
							open = true">
							<span>Nueva Obra Social </span>
						</button>
					</div>
				</div>

				<div class="lg:flex lg:items-center lg:justify-between">
					<div class="min-w-0 flex-1">
						<input class="shadow-sm text-sm border-gray-300 rounded-md" type="text" id="search"
							v-model="search" placeholder="Buscar...">
						<button
							class="ml-2 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
							@click="getObrasSociales()">Buscar</button>
						<button
						class="ml-2 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
						@click="clearFilter()">Limpiar Filtros</button>
						<button
						class="ml-2 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
						@click="viewFavorite()">{{text_favorite}}</button>
						
					</div>

					<div class="mt-5 flex lg:mt-0 lg:ml-4">
						<label class="font-semibold mr-2 mt-2" for="">Ver: </label>
						<select class="text-sm border-gray-300 rounded-md" v-model="length" @change="getObrasSociales">
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
							<th scope="col" class="py-3 px-6" @click="sort_by = 'id', sortObrasSociales()">
								<div class="flex items-center justify-center">
									ID
									<Icons v-if="sort_by == 'id' && sort_order == 'ASC'" name="bars-up"
										class="h-4 w-4 ml-2" />
									<Icons v-else-if="sort_by == 'id' && sort_order == 'DESC'" name="bars-down"
										class="h-4 w-4 ml-2" />
									<Icons v-else name="bars" class="h-4 w-4 ml-2" />
								</div>
							</th>
							<th scope="col" class="py-3 px-6" @click="sort_by = 'title', sortObrasSociales()">
								<div class="flex items-center text-left">
									Obra Social
									<Icons v-if="sort_by == 'title' && sort_order == 'ASC'" name="bars-up"
										class="h-4 w-4 ml-2" />
									<Icons v-else-if="sort_by == 'title' && sort_order == 'DESC'" name="bars-down"
										class="h-4 w-4 ml-2" />
									<Icons v-else name="bars" class="h-4 w-4 ml-2" />
								</div>
							</th>
							<th scope="col" class="py-3 px-6" @click="sort_by = 'visible', sortObrasSociales()">
								<div class="flex items-center justify-center">
									Estado
									<Icons v-if="sort_by == 'visible' && sort_order == 'ASC'" name="bars-up"
										class="h-4 w-4 ml-2" />
									<Icons v-else-if="sort_by == 'visible' && sort_order == 'DESC'" name="bars-down"
										class="h-4 w-4 ml-2" />
									<Icons v-else name="bars" class="h-4 w-4 ml-2" />
								</div>
							</th>
							<th scope="col" class="py-3 px-6">
								<div class="flex items-center justify-center">
									Accion
								</div>
							</th>
						</tr>
						<tr v-for="obras in obrasSociales.data"
							class="bg-white border-b text-center hover:bg-gray-100 focus-within:bg-gray-100">
							<th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
								{{ obras.id }}
							</th>
							<td class="py-4 px-6 text-left">
								<p><b>{{ obras.title }}</b></p>
								<p class="text-sm">{{ obras.description.substr(0,70)}}</p>
							</td>
							<td v-if="obras.visible" class="py-4 px-6">
								<span class="inline-flex items-center justify-center px-2 py-1 mr-2 text-xs font-bold leading-none text-green-100 bg-green-600 rounded-full">Visible</span>
							</td>
							<td v-else class="py-4 px-6">
								<span class="inline-flex items-center justify-center px-2 py-1 mr-2 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">Oculto</span>
							</td>

							<td class="py-4 px-6">
								<a type="button" @click="
									form.id = obras.id,
									form.title = obras.title,
									form.description = obras.description,
									form.visible = obras.visible,
									form.favorite = obras.favorite,
									form.url = obras.url,
									open = true,
									editingObrasSociales = true
								" class=" mr-2 inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-blue-300 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
									<Icons name="edit" class="h-5 w-5"></Icons>
								</a>
								<a v-if="obras.visible" type="button" @click="update_visibilidad(obras.id)" class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-300 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
									<Icons name="arrow-down" class="h-5 w-5"></Icons>
								</a>
								<a v-else type="button" @click="update_visibilidad(obras.id)" class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-green-300 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
									<Icons name="arrow-up" class="h-5 w-5"></Icons>
								</a>

								<a v-if="obras.favorite" type="button" @click="update_favorite(obras.id)" class="ml-2 inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-yellow-300 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
									<Icons name="star" class="h-5 w-5"></Icons>
								</a>
								<a v-else-if="obras.url" obras="button" @click="update_favorite(obras.id)" class="ml-2 inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-gray-300 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
									<Icons name="star" class="h-5 w-5"></Icons>
								</a>
								<a v-else obras="button" class="ml-2 inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
									<Icons name="star" class="h-5 w-5"></Icons>
								</a>

							</td>
						</tr>
					</table>
					<div class="flex justify-between mx-5 px-2 items-center p-2">
						<div>
							Mostrando: {{ this.obrasSociales.from }} a {{ this.obrasSociales.to }} - Entradas encontradas:
							{{ this.obrasSociales.total }}
						</div>

						<div class="flex flex-wrap -mb-1">
							<template v-for="link in obrasSociales.links">
								<div v-if="link.url === null"
									class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded-md"
									v-html="link.label"> </div>
								<div v-else
									class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border border-gray-300 rounded-md hover:bg-indigo-500 hover:text-white cursor-pointer"
									:class="{ 'bg-indigo-500': link.active }, { 'text-white': link.active }"
									@click="getObrasSocialesPaginate(link.url)" v-html="link.label"> </div>
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
							<form class="h-full divide-y divide-gray-200 flex flex-col bg-white shadow-xl" >
								<div class="flex-1 h-0 overflow-y-auto">
									<div class="py-7 px-4 bg-gray-500 sm:px-6">
										<div class="flex items-center justify-between">
											<DialogTitle v-if="editingObrasSociales == false"
												class="text-lg font-medium text-white">
												Nueva Obra Social
											</DialogTitle>

											<DialogTitle v-else class="text-lg font-medium text-white"> Editar
												Obra Social </DialogTitle>
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
										<div class="px-4 divide-y divide-gray-200 sm:px-6 font-medium">

											<div class="mt-4">
												<label for="title" class="block text-sm font-medium text-gray-900">Titulo</label>
												<div class="mt-1">
													<input type="text" v-model="form.title" name="title" id="title"
														class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md" />
												</div>
											</div>
											<div>
												<label for="description"
													class="block text-sm font-medium text-gray-900">Descripcion</label>
												<div class="mt-1">
													<input type="text" v-model="form.description" name="description" id="description"
														class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md" />
												</div>
											</div>
											<div>
												<label for="question" class="block text-sm font-medium text-gray-900">Destacado</label>
												<select v-model="form.favorite" id="driver" name="driver"
														class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
														<option value="0" :bind:select="form.favorite == 0">No</option>
														<option value="1" :bind:select="form.favorite == 1">Si</option>
													</select>
											</div>
											<div>
												<label for="file"
													class="block text-sm font-medium text-gray-900">Logo</label>
												<div class="mt-1">
													<input type="file" name="file" id="file" @input="form.file = $event.target.files[0]"
														class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md" />
												</div>
											</div>
											<div>
												<img class="h-40 mt-2" :src="'/storage/'+this.form.url" alt="" />
											</div>
											<div>
												<p class="text-sm text-gray-400">Se recomienda utilizar imagenes de 300px x 150px</p>
											</div>
										</div>
									</div>
								</div>
								<div class="flex-shrink-0 px-4 py-4 flex justify-end">
									<button type="button"
										class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
										@click="open = false">Cancelar</button>
									<button @click.prevent="submit"
										class="ml-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Guardar</button>
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

import { useForm } from '@inertiajs/inertia-vue3'

export default {
	props: {
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
		useForm
	},
	setup() {
	},
	data() {
		return {
			obrasSociales: "",
			length: "10",
			sort_order: 'DESC',
			sort_by: "id",
			search: "",
			loading: false,
			open: false,
			editingObrasSociales: false,
			form: {},
			toastMessage: "",
			labelType: "info",
			text_favorite: "Ver Destacados",
			view_favorites: false
		}
	},
	watch: {

	},

	created() {
		this.getObrasSociales()
	},

	methods: {

		clearMessage() {
			this.toastMessage = ""
		},
		clearFilter(){
			this.sort_order = 'DESC'
			this.sort_by = "id"
			this.text_favorite = 'Ver Destacados'
			this.view_favorites = false
			this.search = ''
			this.getObrasSociales()
		},
		viewFavorite(){
			if(this.view_favorites){
				this.text_favorite = 'Ver Destacados'
				this.view_favorites = false
			}else{
				this.text_favorite = 'Ver Todos'
				this.view_favorites = true
			}
			this.getObrasSociales()
		},
		onFileChange(e){
                this.form.file = e.target.files[0];
            },
		async getObrasSociales() {

			this.loading = true
			this.obrasSociales = ""
			let filter = `&length=${this.length}`
			filter += `&sort_by=${this.sort_by}`
			filter += `&sort_order=${this.sort_order}`

			if (this.search.length > 0) {
				filter += `&search=${this.search}`
			}

			filter += `&favorite=${this.view_favorites}`
			
			const get = `${route('obras-sociales.list')}?${filter}`

			const response = await fetch(get, { method: 'GET' })
			this.obrasSociales = await response.json()
			this.loading = false
		},
		async getObrasSocialesPaginate(link) {

			var get = `${link}`;
			const response = await fetch(get, { method: 'GET' })

			this.obrasSociales = await response.json()

		},
		sortObrasSociales() {
			this.sort_order = this.sort_order === 'ASC' ? 'DESC' : 'ASC'
			this.getObrasSociales()
		},
		async submit() {
			console.log(this.form.file)
			let rt = '';
			if (this.editingObrasSociales) {
				rt = route('obras-sociales.update');
			} else {
				rt = route('obras-sociales.store');
			}

			const config = {
				headers: { 'Content-Type': 'multipart/form-data' }
			}

			let formData = new FormData();
				formData.append('id', this.form.id);
                formData.append('file', this.form.file);
				formData.append('title', this.form.title);
				formData.append('description', this.form.description);
				formData.append('url', this.form.url);

			axios.post(rt,formData, config).then(response => {
				if (response.status == 200) {
					this.labelType = "success"
					this.toastMessage = response.data.message
					this.getObrasSociales()
				} else {
					this.labelType = "info"
					this.toastMessage = response.data.message
				}
			}).catch(error => {
				this.labelType = "danger"
				this.toastMessage = 'Se ha producido un error'
			})
			this.open = false
		},
		async update_visibilidad(id) {
			let rt = route('obras-sociales.update_visibilidad');
			
			axios.post(rt, {
				id: id,
			}).then(response => {
				if (response.status == 200) {
					this.labelType = "success"
					this.toastMessage = response.data.message
					const pos = this.obrasSociales.data.map(e => e.id).indexOf(id);
                        if(pos >= 0){
                            this.obrasSociales.data[pos].visible = !this.obrasSociales.data[pos].visible
                        }
				} else {
					this.labelType = "info"
					this.toastMessage = response.data.message
				}
			}).catch(error => {
				this.labelType = "danger"
				this.toastMessage = 'Se ha producido un error'
			})
		},
		async update_favorite(id) {
			let rt = route('obras-sociales.update_favorite');
			
			axios.post(rt, {
				id: id,
			}).then(response => {
				if (response.status == 200) {
					this.labelType = "success"
					this.toastMessage = response.data.message
					const pos = this.obrasSociales.data.map(e => e.id).indexOf(id);
                        if(pos >= 0){
                            this.obrasSociales.data[pos].favorite = this.obrasSociales.data[pos].favorite  == 1 ? 0 : 1
                        }
				} else {
					this.labelType = "info"
					this.toastMessage = response.data.message
				}
			}).catch(error => {
				this.labelType = "danger"
				this.toastMessage = 'Se ha producido un error'
			})
		}
	}
}
</script>