// IDC 체크리스트 저장
function saveIDCSupportChecklist() {
    const toBase64String = (content) => {
        return btoa(unescape(encodeURIComponent(content)))
    }

    try {
        let form = $("#frmChecklist");
        let tasks = $("#frmChecklist .doc_task");
        let items = $('#frmChecklist .doc_item');
        let contents = {
            tasks: [],
            lists: []
        };

        tasks.each((index, row)=>{
            contents.tasks.push($(row).val());
        })

        items.each((index, row) => {
            let text = $(row).find(".item_text");
            let type = $(row).find(".item_type");
            
            let tasks = $(row).find(".doc_item_task");
            let confirm = $(row).find(".doc_item_confirm").prop('checked');
            let answers = []


            tasks.each((idx, task)=>{
                answers.push($(task).prop('checked'))
            })

            contents.lists.push({
                "text": text.val(),
                "type": type.val(),
                "answers": answers,
                "confirm": confirm
            })
        })

        let complete = contents.lists.every(x => {
            return x.confirm || x.answers.some(a=>a)
        });

        if (! complete) {
            alert(incompletechecklist[lang_code]);	// 내용을 입력하세요.
            return ;
        }

        //base64 encoding해서 데이터 전송
        let procExec = $("#user_doc_seq").val() ? "UPDATE":"CREATE"
        let procName = toBase64String(getProcName());
        let encTitle = toBase64String($("#doc_title").val());
        let ecnContent = toBase64String(JSON.stringify(contents));

        $("#doc_title_enc").val(encTitle);
        $("#doc_content_enc").val(ecnContent);
        $("#proc_name").val(procName);
        $("#proc_exec").val(procExec);

        $("#doc_title").attr("disabled", true);
        $("#frmChecklist .no_submit").attr("disabled", true);

        let formData = $("#frmChecklist").serialize();
        
        $.post(
            SITE_NAME + '/user/access_info_idc_report_process.php',
            formData,
            (data) => {
                alert(data.msg);
                if (data.status) {
                    try{
                        $("#user_doc_seq").val(data.result)
                        $("#doc_title").attr("disabled", false);
                        form.find(".no_submit").attr("disabled", false);
                    } catch (e) {
                        console.log(e)
                    }
                }
            },
            'json'
        );
    } catch (e) {
        console.log(e)
    }
}

// IDC 유지보수결과서 저장
function saveIDCVisitReport() {
    const toBase64String = (content) => {
        return btoa(unescape(encodeURIComponent(content)))
    }

    try {
		let items = $('#frmVisitReport .report_item');
		let contents = {
			tasks: [],
			lists: []
		};

		for(let idx = 0; idx < items.length; idx++) {
			let item = $(items[idx]);
			let text = item.data('text');
			let type = item.data('type');
			let val  = item.val();

			if (!val) {
				doubleSubmitFlag = false;
				popAlertFocus(item.attr('id'), item.attr('placeholder'));
				return false;
			} else {
				contents.lists.push({
					"text": text,
					"type": type,
					"answer": val
				});
			}
		}


		//base64 encoding해서 데이터 전송
        let procExec = "UPDATE";
        let procName = toBase64String(getProcName());
		let encTitle   = toBase64String($("#doc_title").val());
		let ecnContent = toBase64String(JSON.stringify(contents));

		$("#doc_title_enc").val(encTitle);
		$("#doc_content_enc").val(ecnContent);
        $("#proc_name").val(procName);
        $("#proc_exec").val(procExec);

		$("#frmVisitReport .no_submit").attr("disabled", true);

        let formData = $("#frmVisitReport").serialize();

        $.post(
            SITE_NAME + '/user/access_info_idc_report_process.php',
            formData,
            (data) => {
                alert(data.msg);
                if (data.status) {
                    try{
                        $("#frmVisitReport .no_submit").attr("disabled", false);
                    } catch (e) {
                        console.log(e)
                    }
                }
            },
            'json'
        );
    } catch (e) {
        console.log(e)
    }
}

// IDC 체크리스트 체크박스 토글
function toggleIDCSupportCheckbox() {
    try {
        let checkbox = $(window.event.target).find("input[type='checkbox']");
        let checked  = ! checkbox.attr("checked");
        checkbox.attr("checked", checked);
    } catch (e) {
        console.log(e)
    }
}

//IDC보고서보기
function popUserIdcReport() {
    try {
        let target = window.event.target;
        let v_user_list_seq = $(target).data("seq");
        let user_doc_seq = $(target).data("doc-seq");

        $.post(
            SITE_NAME + '/user/pop_user_idc_report.php',
            {
                'v_user_list_seq' : v_user_list_seq,
                'user_doc_seq' : user_doc_seq
            },
            function (data) {
                $('#popContent').html(data);
                $('#popContent').show();
                EnableScroll(false);
                controllPageExecAuth();
            },
            'text'
        );
    } catch (e) {
        console.log(e)
    }

    return false;
}
