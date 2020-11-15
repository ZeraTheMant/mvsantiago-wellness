class Provider{
	constructor(name, daysav){
		this._name = name;
		this._days_available = daysav;
		this._hours_available = [];
	}
	
	getDaysAvailable(){
		return this._days_available;
	}
	
	addDaysAvailable(day){
		this._days_available.push(day);
	}
	
}