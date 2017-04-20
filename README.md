# Craft Commerce plugin to sort cart/order lineitems by Product Type

### Parameters

$cart: Instance of your `$cart` or `$order` model `Commerce_OrderModel`

$types: Array of Product Types to sort on

$bykey: String of `name` or `id` depending on how you wish to define your `$types`

### Example

By Name:

    {% set types = ["Webinars","Workshops"] %}
    {% set sortedproducts = craft.sortcart.sorted(cart,types,'name') %}

By ID:

    {% set types = [2,7] %}
    {% set sortedproducts = craft.sortcart.sorted(cart,types,'id') %}


Example Markup:

    {% set types = ["Webinars","Workshops"] %}
    {% set sortedproducts = craft.sortcart.sorted(cart,types,'name') %}
    {% for sorted in sortedproducts %}
        {% if sorted.type != "unsorted" %}
            <h2>{{sorted.type}}</h2>
            {% if sorted.items is defined %}
                {% for item in sorted.items %}
                    <ul>
                    <li>QTY:    {{item.qty}}</li>
                    <li>Title:  {{item.purchasable.product.title}}</li>
                    <li>SKU:    {{item.purchasable.sku}}</li>
                    <li>Price:  {{item.purchasable.price}}</li>
                    {% if item.qty > 1 %}
                    <li>Total:  {{item.total}}</li>
                    {% endif %}
                    </ul>
                {% endfor %}
            {% endif %}
            <hr>
        {% else %}
            Markup for standard products...
        {% endif %}
    {% endfor %}

Notice Product Types not defined in `$types` will be keyed under "unsorted".