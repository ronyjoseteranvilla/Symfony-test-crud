 const article = document.getElementById('article');
 if(article){
     article.addEventListener('click', e =>{
         if(e.target.className === 'btn btn-danger delete-article'){
             if(confirm('Are you sure')){
                 const id = e.target.getAttribute('data-id');
                
                 fetch(`/article/delete/${id}`, {
                     method: 'DELETE'
                 }).then(res => window.location.reload());
             }
         }
     });
 }