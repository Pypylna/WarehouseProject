Serwis do inwentaryzacji magazynów/sklepów.
Dodajesz sobie magazyn. Dodajesz produkty. Produkty są pokategoryzowane i przypisane do drzewka kategorii. Produkty mogą być trwałe (np części do samochodów), jak i z datą ważności (np jedzenie). Każdy produkt ma też podaną jego ilość w magazynie na stanie, jak i cenę i kod produktu. Jeden magazyn może należeć do jednej sieci (magazynów/sklepów). Kategorie i ilość produktów są współdzielone w sieci (jest informacja, że produkt jest dostępny, tyle że w innym magazynie).

Twoim zadaniem jest stworzenie odpowiedniego backendu tak, by osoba z niego korzystająca mogła dodawać/pomniejszać ilość produktów, łatwo odnajdować te niedługo się kończące (wg pozostałej ilości) oraz te, które niedługo się zepsują (wg daty ważności). Może też listować sobie produkty w sklepie/magazynie (widzi wtedy w pierwszej kolejności produkty w danym magazynie, dopiero później w sieci).

Funkcje możesz wymyślić też sama, ja tutaj podaję tylko Twój cel do zrobienia.

Widzę to tak (wstępny zarys, którego nie musisz się trzymać):

StoreGroups:
* name
* stores

Store:
* name
* localization
* storeGroup

Category:
* store/storeGroup
* name
* parent
* childrens

Product:
* name
* description
* price
* category
* keywords
* exprireAt
* store
* amount


Pułapki: Produkty mogą występować w grupach (np "mleko końskie 1l" w ilości 1000szt, z czego 200 zepsuje się jutro, 600 pojutrze, a reszta popojutrze). Moja propozycja - MetaProdukty.

MetaProduct:
* name
* description
* price
* category
* keywords
* store
* products

Product:
* metaProduct
* expireAt

Powyższe oznacza, że 1000 sztuk mleka "końskiego 1l" na magazynie oznacza istnienie 1000 wpisów w tabeli "products".