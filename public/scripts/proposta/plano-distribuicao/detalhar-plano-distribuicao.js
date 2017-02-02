 // register
Vue.component('my-component', {
  template: '#app-6',
    data: function() {
       return {
            "dsProduto": '', // Categoria
            "qtExemplares": 0, // Quantidade de exemplar / Ingresso
            "qtDistribuicaoGratuitaDivulgacao" : 0,
            "qtDistribuicaoGratuitaPatrocinador": 0,
            "qtDistribuicaoGratuitaPopulacao": 0,
            "vlUnitarioPrecoPopularValorIntegral": 0.0, // Preço popular: Preço Unitario do Ingresso
            "precoUnitarioIngressoProponente": 0.0, // Proponente: Preço Unitario do Ingresso
            produto:{ },
            produtos:  [],
           active : false,
           icon : 'add'
        }
    },
    props:['idpreprojeto','idplanodistribuicao', 'idmunicipioibge', 'iduf' ],
    computed:{
        // preço popular: Quantidade de Inteira
        qtPrecoPopularValorIntegral: function() {
            return ((this.qtExemplares * 0.5)  - (parseInt(this.qtDistribuicaoGratuitaDivulgacao) + parseInt(this.qtDistribuicaoGratuitaPatrocinador) + parseInt(this.qtDistribuicaoGratuitaPopulacao))) * 0.6 ;
        },
        // preço popular: Quantidade de Meia Entrada
        qtPrecoPopularValorParcial: function () {
            return  ( ((this.qtExemplares * 0.5) - (parseInt(this.qtDistribuicaoGratuitaDivulgacao) + parseInt(this.qtDistribuicaoGratuitaPatrocinador) + parseInt(this.qtDistribuicaoGratuitaPopulacao))) *0.4 );
        },
        vlReceitaPrecoPopularValorIntegral: function(){
            return parseInt(this.qtPrecoPopularValorIntegral) * parseFloat(this.vlUnitarioPrecoPopularValorIntegral);
        },
        vlReceitaPrecoPopularValorParcial: function() {
            return this.qtPrecoPopularValorParcial * ( this.vlUnitarioPrecoPopularValorIntegral * 0.5);
        },
        qtPrecoProponenteValorIntegral: function() {
            return (this.qtExemplares * 0.5) * 0.6 ;
        },
        qtPrecoProponenteValorParcial: function() {
            return (this.qtExemplares * 0.5) * 0.4 ;
        },
        vlReceitaPrecoProponenteValorIntegral: function(){
            return parseFloat( this.precoUnitarioIngressoProponente * this.qtPrecoProponenteValorIntegral ).toFixed(2);
        },
        vlReceitaPrecoProponenteValorParcial: function(){
            return parseFloat( ( this.precoUnitarioIngressoProponente * 0.5 ) * this.qtPrecoProponenteValorParcial).toFixed(2);
        },
        vlReceitaPrevista: function() {
            return parseFloat(this.vlReceitaPrecoPopularValorIntegral) + parseFloat(this.vlReceitaPrecoPopularValorParcial)
                + parseFloat(this.vlReceitaPrecoProponenteValorIntegral) + parseFloat(this.vlReceitaPrecoProponenteValorParcial);
        },
        quantidadeTotal: function() {
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length; i++){
                total += parseInt(this.produtos[i]['qtExemplares']);
            }
            return total;
        },
         divulgacaoTotal:function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                  total += this.produtos[i]['divulgacao'];
            }
            return total;
        },
         populacaoTotal :function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                  total += this.produtos[i]['populacao'];
            }
            return total;
        },
         patrocinadorTotal :function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                  total += parseInt(this.produtos[i]['patrocinador']);
            }
            return total;
        },
         inteiraTotal :function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                total += parseInt(this.produtos[i]['qtPrecoPopularValorIntegral']);
            }
            return total;
        },
         meiaEntradaTotal:function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                  total += parseInt(this.produtos[i]['meiaEntrada']);
            }
            return total;
        },
         precoUnitarioIngressoTotal :function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                  total += parseFloat(this.produtos[i]['vlUnitarioPrecoPopularValorIntegral']);
            }
            return total;
        },
         valorInteiraTotal :function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                  total += parseFloat(this.produtos[i]['valorInteira']);
            }
            return total;
        },
         valorMeiaEntradaTotal:function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                  total += parseFloat(this.produtos[i]['valorMeiaEntrada']);
            }
            return total;
        },
         quantidadeInteiraTotal:function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                  total += parseInt(this.produtos[i]['qtPrecoProponenteValorIntegral']);
            }
            return total;
        },
         quantidadeMeiaEntradaTotal:function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                  total += parseInt(this.produtos[i]['quantidadeMeiaEntrada']);
            }
            return total;
        },
        precoUnitarioIngressoProponenteTotal:function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                  total += parseFloat(this.produtos[i]['precoUnitarioIngressoProponente']);
            }
            return total;

        },
        valorInteiraProponenteTotal:function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                  total += parseFloat(this.produtos[i]['vlReceitaPrecoProponenteValorIntegral']);
            }
            return total.toFixed(2);
        },
        valorMeiaEntradaProponenteTotal:function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                total += parseFloat(this.produtos[i]['vlReceitaPrecoProponenteValorParcial']);
            }
            return total.toFixed(2);
        },
        receitaPrevistaTotal:function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                total += parseFloat(this.produtos[i]['vlReceitaPrevista']);
            }
            return total;
        },
    },
    watch:{
        //Quantidade de exemplar / Ingresso
        qtExemplares: function(val)  {
            this.qtDistribuicaoGratuitaDivulgacao = this.qtExemplares * 0.1;
            this.qtDistribuicaoGratuitaPatrocinador = this.qtExemplares * 0.1;
            this.qtDistribuicaoGratuitaPopulacao = parseInt(this.qtExemplares * 0.1);
        },
        //Distribuição Gratuita: Divulgação
        divulgacao: function(val)  {
            quantidade = this.qtExemplares * 0.1;
            if(val > quantidade) {
                alert("Valor n&atilde;o pode passar de: "+quantidade);
                this.qtDistribuicaoGratuitaDivulgacao = this.qtExemplares * 0.1;
            }
        },
        //Distribuição Gratuita: Patrocinador
        patrocinador: function(val)  {
            quantidade = this.qtExemplares * 0.1;
            if(val > quantidade) {
                alert("Valor n&atilde;o pode passar de: "+quantidade);
                this.qtDistribuicaoGratuitaPatrocinador = this.qtExemplares * 0.1;
            }
        },
        vlUnitarioPrecoPopularValorIntegral: function() {
            if (this.vlUnitarioPrecoPopularValorIntegral > 50.00) {
                alert('O valor não pode ser maior que 50.00');
                this.vlUnitarioPrecoPopularValorIntegral = 50.00;
            }
        },
        //produtos:function(){
            //this.t();
        //}
    },
    beforeUpdate: function() {

        this.valorInteira = parseFloat((this.qtPrecoPopularValorIntegral * this.vlUnitarioPrecoPopularValorIntegral)).toFixed(2);
        this.valorMeiaEntrada = parseFloat( (this.vlUnitarioPrecoPopularValorIntegral * 0.5) * this.qtPrecoPopularValorParcial );
        this.qtExemplaresInteira = ((this.qtExemplares *0.5) * 0.6);
        this.qtExemplaresMeiaEntrada = ((this.qtExemplares *0.5) * 0.4);
    },
    mounted: function(){
        this.t();
    },
    methods: {
        t: function(){
            var vue = this;

            url = "/proposta/plano-distribuicao/detalhar-mostrar/idPreProjeto/"+this.idpreprojeto+"?idPlanoDistribuicao=" + this.idplanodistribuicao + "&idMunicipio=" + this.idmunicipioibge +"&idUF=" + this.iduf
            $3.ajax({
              type: "GET",
              url:url
            })
            .done(function(data) {
                vue.$data.produtos = data.data;
            })
            .fail(function(){ alert('error'); });
        },
        salvar:function(event){

            p = {
                idPlanoDistribuicao: this.idplanodistribuicao,
                idUF: this.iduf,
                idMunicipio: this.idmunicipioibge,
                dsProduto: this.dsProduto,
                qtExemplares: this.qtExemplares,
                qtDistribuicaoGratuitaDivulgacao: this.qtDistribuicaoGratuitaDivulgacao,
                qtDistribuicaoGratuitaPatrocinador: this.qtDistribuicaoGratuitaPatrocinador,
                qtDistribuicaoGratuitaPopulacao: this.qtDistribuicaoGratuitaPopulacao,
                qtPrecoPopularValorIntegral: this.qtPrecoPopularValorIntegral,
                qtPrecoPopularValorParcial: this.qtPrecoPopularValorParcial,
                vlUnitarioPrecoPopularValorIntegral: this.valorInteira,
                qtPrecoProponenteValorIntegral : this.qtExemplaresInteira,
                qtPrecoProponenterValorParcial: this.qtExemplaresMeiaEntrada,
                vlUnitarioPrecoProponenteValorIntegral: this.vlReceitaPrecoProponenteValorIntegral,
                vlUnitarioPrecoProponenteValorParcial: this.vlUnitarioPrecoProponenteValorParcial,
                vlReceitaPrecoPopularValorIntegral:this.vlReceitaPrecoPopularValorIntegral,
                vlReceitaPrecoPopularValorParcial:this.vlReceitaPrecoPopularValorParcial,
                vlReceitaPrecoProponenteValorIntegral : this.vlReceitaPrecoProponenteValorIntegral,
                vlReceitaPrecoProponenteValorParcial : this.vlReceitaPrecoProponenteValorParcial,
                vlReceitaPrevista: this.vlReceitaPrevista,
            }

            vue = this;
            $3.ajax({
              type: "POST",
              url: "/proposta/plano-distribuicao/detalhar-salvar/idPreProjeto/" + this.idpreprojeto,
              data: p,
            })
            .done(function() {
            })
            .fail(function(){ alert('error'); });
            this.t();
        },
        excluir: function(index){
            this.produtos.splice(index, 1)
            event.preventDefault();
        },
        populacaoValidate: function(val){
            quantidade = this.qtExemplares * 0.1;
            if(val < quantidade) {
                alert("Valor n&atilde;o pode ser menor que: "+quantidade);
                this.qtDistribuicaoGratuitaPopulacao = this.qtExemplares * 0.1;
            }

            if((parseInt( this.qtDistribuicaoGratuitaDivulgacao ) + parseInt(this.qtDistribuicaoGratuitaPatrocinador) + parseInt(this.qtDistribuicaoGratuitaPopulacao)) > (this.qtExemplares * 0.3)) {
                alert("A soma dos valores de divulga&ccedil;&atilde;o, patrocinador e popula&ccedil;&atilde;o n&atilde;o pode passar de 30%");
                this.$refs.patrocinador.focus();
            }
        },
        mostrar: function() {
            this.active = this.active == true ? false: true ;
            this.icon = this.icon == 'visibility_off' ? 'add': 'visibility_off';
        }
    }
})

var app6 = new Vue({
        el: '#example',
    })
