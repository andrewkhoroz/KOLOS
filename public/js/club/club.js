var clubObject = {
    nicEditorObj:null,
    init:function(){
        $("input[name=match_date]:visible").datepicker({
            firstDay: 1,
            dateFormat: 'yy/mm/dd',
            dayNamesMin: ['Нд','Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
            monthNames: ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень',
            'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень']
        });
        toolbar.initNicedit();
        toolbar.initUploading();
    }
};

