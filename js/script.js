async function taskCheck() {
	const taskAll = document.querySelectorAll('.list-group-item'); 

	for (let y = 0; y < taskAll.length; y++) {

		const task = taskAll[y];
		var check = task.querySelector('.form-check-input');
		
		
	
		check.addEventListener('change', function (e) {

			var label_task = e.target.nextSibling;
			
			var idCheck = e.target.id;
			
			if(e.target.checked){
				label_task.style.textDecoration = 'line-through';

				var formData = new FormData();

				formData.append('check_task', idCheck);
				$.ajax({
					type: "POST",
					url: 'request.php',
					cache: false,
					contentType: false,
					processData: false,
					data: formData,
					beforeSend: function () {},
					success: function (result) {}

				});

			} else {
				label_task.style.textDecoration = 'none';

				var formData = new FormData();

				formData.append('uncheck_task', idCheck);
				$.ajax({
					type: "POST",
					url: 'request.php',
					cache: false,
					contentType: false,
					processData: false,
					data: formData,
					beforeSend: function () {},
					success: function (result) {}

				});
			}
		})



	}

}
taskCheck();