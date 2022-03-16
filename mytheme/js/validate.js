function validate(e) {
 
 let valid = true;

 // удаляем все уже существующие ошибки валидации, чтобы проверять по новой
 const errors = document.getElementsByClassName( 'validation-error' );
 while( errors.length > 0 ){
     errors[0].parentNode.removeChild( errors[0] );
 }

 // проверяем Название
 const titleField = document.getElementById( "title" );

 if ( ! titleField.value ) { // если не заполнено
     document.querySelector( 'label[for="title"]' ).innerHTML += ' <span class="validation-error">Укажите назване</span>';
     valid = false;
 }

 // проверяем поле Описание
 const descriptionField = document.getElementById( "description" );

 if ( ! descriptionField.value ) { // если не заполнено
     document.querySelector( 'label[for="description"]' ).innerHTML += ' <span class="validation-error">Добавьте описание</span>';
     valid = false;
 }


// проверяем поле Тип ставки

const betTypesField = document.getElementById( "bet_type" );

 if ( betTypesField.value == '' ) { // если не заполнено
     document.querySelector( 'label[for="bet_type"]' ).innerHTML += ' <span class="validation-error">Выберите тип взятки</span>';
     valid = false;
 }

 if( false == valid ) {
     e.preventDefault(); // предотвращаем отправку формы, если есть ошибки валидации
     
 }
 return valid;

}

const form = document.getElementById( 'betForm' );

if (form) {
    form.addEventListener( 'submit', validate );
}