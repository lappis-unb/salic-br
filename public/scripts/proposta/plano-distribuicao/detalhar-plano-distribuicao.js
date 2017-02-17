(function (global, factory) {
    if (typeof define === 'function' && define.amd) {
        define(['../numeral'], factory);
    } else if (typeof module === 'object' && module.exports) {
        factory(require('../numeral'));
    } else {
        factory(global.numeral);
    }
}(this, function (numeral) {
    numeral.register('locale', 'pt-br', {
        delimiters: {
            thousands: '.',
            decimal: ','
        },
        abbreviations: {
            thousand: 'mil',
            million: 'milhões',
            billion: 'b',
            trillion: 't'
        },
        ordinal: function (number) {
            return 'º';
        },
        currency: {
            symbol: 'R$'
        }
    });
}));

// switch between locales
numeral.locale('pt-br');

// register
Vue.component('input-money', {
    template: '<div>\
                <input\
                    v-bind:value="value"\
                    ref="input"\
                    v-on:input="updateMoney($event.target.value)"\
                    v-on:blur="formatValue"\
                >\
                </div>',
    props: {
        value: {
          type: Number,
          default: 0
        }
    },
    data:function(){
        return {
           val:1
        }
    },
    mounted: function () {
        this.formatValue()
    },
    methods:{
        formatValue: function () {
          this.$refs.input.value = numeral(this.$refs.input.value).format('0,0.00');
        },
        updateMoney: function(value) {
            console.log(value);
            this.val = value;
            this.$emit('ev', this.val)
        }
    }
})

// register
Vue.component('my-component', {
  template: '#app-6',
    data: function() {
       return {
            "dsProduto": '', // Categoria
            "qtExemplares": 0, // Quantidade de exemplar / Ingresso
            "qtGratuitaDivulgacao" : 0,
            "qtGratuitaPatrocinador": 0,
            "qtGratuitaPopulacao": 0,
            "vlUnitarioPopularIntegral": 0.0, // Preço popular: Preço Unitario do Ingresso
            "qtPrecoPopularValorIntegral" : 0, //Preço Popular: Quantidade de Inteira
            "qtPrecoPopularValorParcial": 0,//Preço Popular: Quantidade de meia entrada
            "vlUnitarioProponenteIntegral": 0,
            "qtPopularIntegral": 0,
            "qtPopularParcial": 0,
            produto:{ }, // produto sendo manipulado
            produtos:  [], // lista de produtos
            active : false,
            icon : 'add'
        }
    },
    props:['idpreprojeto','idplanodistribuicao', 'idmunicipioibge', 'iduf' ],
    computed:{
        // Limite: preço popular: Quantidade de Inteira
        qtPrecoPopularValorIntegralLimite: function() {
            return ((this.qtExemplares * 0.5)  - (parseInt(this.qtGratuitaDivulgacao) + parseInt(this.qtGratuitaPatrocinador) + parseInt(this.qtGratuitaPopulacao))) * 0.6 ;
        },
        // Limite: preço popular: Quantidade de meia entrada
        qtPrecoPopularValorParcialLimite: function () {
            return  ( ((this.qtExemplares * 0.5) - (parseInt(this.qtGratuitaDivulgacao) + parseInt(this.qtGratuitaPatrocinador) + parseInt(this.qtGratuitaPopulacao))) *0.4 );
        },
        //Preço Popular: Valor da inteira
        vlReceitaPopularIntegral: function() {
            return parseInt(this.qtPopularIntegral) * parseFloat(this.vlUnitarioPopularIntegral);
        },
        vlReceitaPopularParcial: function() {
            return this.qtPopularParcial * ( this.vlUnitarioPopularIntegral * 0.5);
        },
        qtProponenteIntegral: function() {
            return (this.qtExemplares * 0.5) * 0.6 ;
        },
        qtProponenteParcial: function() {
            return (this.qtExemplares * 0.5) * 0.4 ;
        },
        vlReceitaProponenteIntegral: function() {
            return parseFloat( this.vlUnitarioProponenteIntegral * this.qtProponenteIntegral ).toFixed(2);
        },
        vlReceitaProponenteParcial: function(){
            return parseFloat( ( this.vlUnitarioProponenteIntegral * 0.5 ) * this.qtProponenteParcial).toFixed(2);
        },
        vlReceitaPrevista: function() {
            return parseFloat(this.vlReceitaPopularIntegral) + parseFloat(this.vlReceitaPopularParcial)
                + parseFloat(this.vlReceitaProponenteIntegral) + parseFloat(this.vlReceitaProponenteParcial);
        },
        // Total de exemplares
        qtExemplaresTotal: function() {
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length; i++){
                total += parseInt(this.produtos[i]['qtExemplares']);
            }
            return total;
        },
        // Total de divulgação gratuita.
        qtGratuitaDivulgacaoTotal: function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                total += this.produtos[i]['qtGratuitaDivulgacao'];
            }
            return total;
        },
        // Total de divulgação Patrocinador
        qtGratuitaPatrocinadorTotal: function() {
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++) {
                total += parseInt(this.produtos[i]['qtGratuitaPatrocinador']);
            }
            return total;
        },
        // Total de divulgação gratuita.
        qtGratuitaPopulacaoTotal: function() {
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++) {
                total += this.produtos[i]['qtGratuitaPopulacao'];
            }
            return total;
        },
        //Preço Popular: Quantidade de Inteira
        qtPopularIntegralTotal: function() {
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                total += parseInt(this.produtos[i]['qtPopularIntegral']);
            }
            return total;
        },
        //Preço Popular: Quantidade de mei entrada
        qtPopularParcialTotal:function() {
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                total += parseInt(this.produtos[i]['qtPopularParcial']);
            }
            return total;
        },
        vlReceitaPopularIntegralTotal :function() {
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                total += parseFloat(this.produtos[i]['vlReceitaPopularIntegral']);
            }
            return numeral(total).format('0,0.00');
        },
        vlReceitaPopularParcialTotal: function() {
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++) {
                total += parseFloat(this.produtos[i]['vlReceitaPopularParcial']);
            }
            return numeral(total).format('0,0.00');
        },
        qtProponenteIntegralTotal:function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                total += parseInt(this.produtos[i]['qtProponenteIntegral']);
            }
            return total;
        },
        qtProponenteParcialTotal:function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                total += parseInt(this.produtos[i]['qtProponenteParcial']);
            }
            return total;
        },
        vlReceitaProponenteIntegralTotal:function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                  total += parseFloat(this.produtos[i]['vlReceitaProponenteIntegral']);
            }
            return numeral(total).format('0,0.00');
        },
        vlReceitaProponenteParcialTotal: function() {
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                total += parseFloat(this.produtos[i]['vlReceitaProponenteParcial']);
            }
            return numeral(total).format('0,0.00');
        },
        receitaPrevistaTotal:function(){
            total = 0 ;
            for ( var i = 0 ; i < this.produtos.length ; i++){
                total += parseFloat(this.produtos[i]['vlReceitaPrevista']);
            }
            return numeral(total).format('0,0.00');
        },
    },
    watch:{
        //Quantidade de exemplar / Ingresso
        qtExemplares: function(val)  {
            this.qtGratuitaDivulgacao = this.qtExemplares * 0.1;
            this.qtGratuitaPatrocinador = this.qtExemplares * 0.1;
            this.qtGratuitaPopulacao = parseInt(this.qtExemplares * 0.1);
            this.qtPopularIntegral = ((this.qtExemplares * 0.5)  - (parseInt(this.qtGratuitaDivulgacao) + parseInt(this.qtGratuitaPatrocinador) + parseInt(this.qtGratuitaPopulacao))) * 0.6 ;
            this.qtPopularParcial =  ( ((this.qtExemplares * 0.5) - (parseInt(this.qtGratuitaDivulgacao) + parseInt(this.qtGratuitaPatrocinador) + parseInt(this.qtGratuitaPopulacao))) *0.4 );
        },
        //Distribuição Gratuita: Divulgação
        qtGratuitaDivulgacao: function(val)  {
            quantidade = this.qtExemplares * 0.1;
            if(val > quantidade) {
                alert("Valor n&atilde;o pode passar de: "+quantidade);
                this.qtGratuitaDivulgacao = this.qtExemplares * 0.1;
            }
        },
        //Distribuição Gratuita: Patrocinador
        patrocinador: function(val)  {
            quantidade = this.qtExemplares * 0.1;
            if(val > quantidade) {
                alert("Valor n&atilde;o pode passar de: "+quantidade);
                this.qtGratuitaPatrocinador = this.qtExemplares * 0.1;
            }
        },
        vlUnitarioPopularIntegral: function() {
            if (this.vlUnitarioPopularIntegral > 50.00) {
                alert('O valor não pode ser maior que 50.00');
                this.vlUnitarioPopularIntegral = 50.00;
            }
        },
        qtPrecoPopularValorIntegral: function(val){
            if (this.qtPrecoPopularValorIntegral > this.qtPrecoPopularValorIntegralLimite) {
                alert('O valor não pode ser maior que ' + this.qtPrecoPopularValorIntegralLimite);
            }
        },
        qtPrecoPopularValorParcial: function(val){
            if (this.qtPrecoPopularValorParcial > this.qtPrecoPopularValorParcialLimite) {
                alert('O valor não pode ser maior que ' + this.qtPrecoPopularValorParcialLimite);
            }
        }
    },
    mounted: function() {
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
                qtExemplares : this.qtExemplares,
                qtGratuitaDivulgacao :this.qtGratuitaDivulgacao,
                qtGratuitaPatrocinador : this.qtGratuitaPatrocinador,
                qtGratuitaPopulacao : this.qtGratuitaPopulacao,
                qtPopularIntegral :this.qtPopularIntegral,
                qtPopularParcial : this.qtPopularParcial,
                vlUnitarioPopularIntegral : this.vlUnitarioPopularIntegral,
                vlReceitaPopularIntegral : this.vlReceitaPopularIntegral,
                vlReceitaPopularParcial : this.vlReceitaPopularParcial,
                qtProponenteIntegral : this.qtProponenteIntegral,
                qtProponenteParcial : this.qtProponenteParcial,
                vlUnitarioProponenteIntegral : this.vlUnitarioProponenteIntegral,
                vlReceitaProponenteIntegral : this.vlReceitaProponenteIntegral,
                vlReceitaProponenteParcial : this.vlReceitaProponenteParcial,
                vlReceitaPrevista : this.vlReceitaPrevista,
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
            //this.produtos.splice(index, 1)

            $3.ajax({
                type: "POST",
                url: "/proposta/plano-distribuicao/detalhar-excluir/idPreProjeto/" + this.idpreprojeto,
                data: {idDetalhaPlanoDistribuicao: index, idPlanoDistribuicao: this.idplanodistribuicao},
            })
            .done(function() {
                alert("Excluido com sucesso");
            });
            this.t();
        },
        populacaoValidate: function(val){
            quantidade = this.qtExemplares * 0.1;
            if(val < quantidade) {
                alert("Valor n&atilde;o pode ser menor que: "+quantidade);
                this.qtGratuitaPopulacao = this.qtExemplares * 0.1;
            }

            if((parseInt( this.qtGratuitaDivulgacao ) + parseInt(this.qtGratuitaPatrocinador) + parseInt(this.qtGratuitaPopulacao)) > (this.qtExemplares * 0.3)) {
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
