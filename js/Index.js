class Operaciones {
    constructor(num1, num2) {
        this.num1 = num1;
        this.num2 = num2;
    }
    suma() {
        return this.num1 + this.num2;
    }

}
function suma() {
    num1 = Number(document.getElementById("num1").value);
    num2 = Number(document.getElementById("num2").value);
    re = new Operaciones(num1, num2).suma();
    document.getElementById("re").innerHTML = '<h5 style="color: red;">' + re + '</h5>';


}
function altura() {

    num2 = Number(document.getElementById("altura").value);
    if (num2 > 180) {
        document.getElementById("dig").innerHTML = '<h5 style="color: red;">Usted es alto</h5>';
    } else {
        document.getElementById("dig").innerHTML = '<h5 style="color: red;">Usted no  es alto</h5>';
    }
    nombre=["asdasd","rtyrty",21121];
    nombre.forEach(element => {
       console.log(element); 
    });
    return 34324;

}
persona={
    nombre: "Juan",
    edad: 30,
    profesion: "Ingeniero",
    saludar: function() {
        return `Hola, mi nombre es ${this.nombre}, tengo ${this.edad} años y soy ${this.profesion}.`;
    }
}
persona.saludar()