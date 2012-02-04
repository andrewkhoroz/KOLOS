function Coffee() {
    this.cost = function() {
        return 1;
    };
}

// Decorator A
function Milk(coffee) {
    this.cost = function() {
        return coffee.cost() + 0.5;
    };
}

// Decorator B
function Whip(coffee) {
    this.cost = function() {
        return coffee.cost() + 0.7;
    };
}

// Decorator C
function Sprinkles(coffee) {
    this.cost = function() {
        return coffee.cost() + 0.2;
    };
}

// Here's one way of using it
var coffee = new Milk(new Whip(new Sprinkles(new Coffee())));
alert( coffee.cost() );

// Here's another
var coffee = new Coffee();
coffee = new Sprinkles(coffee);
coffee = new Whip(coffee);
coffee = new Milk(coffee);
alert(coffee.cost());

