var app = new Vue({
  el: '#main',
  data() {
      return {
          currentTab: 1,
          cargando: false,
          consultaDatos: false,
          links:[
            'antecedentes_familiares_tab',
            'factores_riesgo_tab',
          ],
          ubicacion_link:0,
          consultaDatosTipoEvaluacion:[],
          consultaDatosGenero:[],
          consultaDatosTipoSangre:[],
          consultaDatosReligion:[],
          consultaDatosLateralidad:[],
          consultaDatosOrientacionSexual:[],
          consultaDatosIdentidadGenero:[],
          consultaDatosDiscapacidades:[],
      }
  },
  created: function () {
      this.limpiarData();
      this.consultaCombosRegistro();
  },
  methods: {
      ubicacionTab:function(id){
              var key=this.links.indexOf(id);
              this.ubicacion_link=key;
      },
      tabAdelante:function(){
          this.ubicacion_link=this.ubicacion_link+1;
          let tamano=this.links.length;
          var id='informacion_general_tab';
          if(tamano-1<this.ubicacion_link)   this.ubicacion_link=0;
          else id=this.links[this.ubicacion_link];
          document.querySelector("#"+id+"").click();
      },
      tabAtras:function(){
          this.ubicacion_link=this.ubicacion_link-1;
          var id='informacion_general_tab';
          if(this.ubicacion_link==-1)   this.ubicacion_link=0;
          else id=this.links[this.ubicacion_link];
          document.querySelector("#"+id+"").click();
      },
      limpiarData: function () {
      },
      limpiarDiscapacidad:function(){
      },
      llenarPaciente: function(data) {
      },
      async buscarFuncionario() {
      },
      async editarRegistro() {
      },
      async consultaCombosRegistro(){
      },      
      async guardarRegistro() {
      },
      async guardarDiscapacidad() {
      },
      async guardarAntecedentesPersonalesQuirurgicos(tipo){},
      async eliminarAntecedentes(id,tipo) {},
  }
});