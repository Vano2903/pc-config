import requests
import random
import os
from bs4 import BeautifulSoup
from urllib.parse import urljoin

base_url = "https://www.google.com"


# use


def downloadimages(search_term, resolution):  # Define the function to download images
    print(
        f"https://source.unsplash.com/random/{resolution}/?"
        + str(search_term)
        + ", allow_redirects=True"
    )  # State the URL

    response = requests.get(
        f"https://source.unsplash.com/random/{resolution}/?"
        + str(search_term)
        + ", allow_redirects=True"
    )
    return response.content


def extract_image_url(name):
    query = f"{name}%20image"
    url = f"https://www.google.com/search?q={name}+image&tbm=isch&tbs=isz:l"

    print(query)
    print(url)
    # Send a request to Google Images with the query
    response = requests.get(url, headers={"User-Agent": "Mozilla/5.0"})

    # Parse the response content using BeautifulSoup
    soup = BeautifulSoup(response.content, "html.parser")

    # Find the first image element in the parsed HTML
    # Find the 15th image element in the parsed HTML
    # go back 2 parents
    # get the href
    image_element = soup.find_all("img")[1].parent.parent.get("href")
    print("image_element:", image_element)
    url = urljoin("https://www.google.com", image_element)
    print("url1:", url)
    response = requests.get(url, headers={"User-Agent": "Mozilla/5.0"})
    soup = BeautifulSoup(response.content, "html.parser")
    image_element = soup.find_all("img")[2]

    # Extract the image URL from the 'src' attribute of the image element
    if image_element:
        image_url = image_element.get("src")
        print(image_url)
        # Check if the URL is relative and prepend it with the base URL
        if image_url.startswith("/"):
            image_url = urljoin(base_url, image_url)

        return image_url

    return None


class category:
    def __init__(self, name):
        self.name = name
        self.image = None

    def create(self):
        # find a random image of self.name on google
        # save image to /images/category_{self.name}.jpg
        # set self.image to /images/category_{self.name}.jpg
        # Specify the category name (replace 'category_name' with the actual category name)

        # Set the Google search query for the category image (replace 'category_name' with the actual category name)

        # print(response.status_code)

        # # Extract the image URL from the response content (you might need to install a library like BeautifulSoup for this)
        # # Replace 'image_url' with the actual code to extract the image URL from the response content
        image_url = extract_image_url(self.name)
        # image = downloadimages(self.name, "1080x1920")
        # Create the directory if it doesn't exist
        directory = "images"
        os.makedirs(directory, exist_ok=True)

        # Generate the image file path
        image_path = f"{directory}/category_{self.name}.jpg"

        # Download the image from the URL and save it to the specified path
        with open(image_path, "wb") as file:
            image_response = requests.get(image_url)
            file.write(image_response.content)

        # Set the image path to the category object (replace 'self.image' with the actual variable name)
        self.image = image_path

    def __str__(self):
        return f"('{self.name}', '{self.image}',1)"


class brand:
    # categoryID
    # name
    # defaultImage
    def __init__(self, categoryID, name):
        self.categoryID = categoryID
        self.name = name
        self.defaultImage = None

    def __str__(self):
        return f"({self.categoryID}, '{self.name}', '{self.defaultImage}')"


class component:
    # name, categoryID, brandID, description, price, discountPrice, availability, reviewUrl, image
    def __init__(self, name, categoryID, brandID):
        self.name = name
        self.categoryID = categoryID
        self.brandID = brandID
        self.description = None
        self.price = None
        self.reviewUrl = None
        self.image = None

    def __str__(self):
        # set availability to 0 10% of the time
        availability = 1
        if random.randint(0, 10) == 0:
            availability = 0

        discount = 0
        if random.randint(0, 10) == 0:
            discount = random.randint(0, 90)

        return f"('{self.name}', {self.categoryID}, {self.brandID}, '{self.description}', {self.price}, {discount}, {availability}, '{self.reviewUrl}', '{self.image}')"


categories = [
    "CPU",
    "motherboard",
    "GPU",
    "RAM",
    "PSU",
    "harddrive",
    "Computer Case",
    "heat sink",
]

brands = [
    (1, "intel"),
    (1, "amd"),
    (2, "asus"),
    (2, "msi"),
    (2, "gigabyte"),
    (2, "asrock"),
    (3, "asus"),
    (3, "amd"),
    (3, "msi"),
    (3, "gigabyte"),
    (3, "asrock"),
    (4, "corsair"),
    (4, "g.skill"),
    (4, "kingston"),
    (4, "crucial"),
    (5, "corsair"),
    (5, "evga"),
    (5, "seasonic"),
    (5, "be quiet!"),
    (6, "samsung"),
    (6, "seagate"),
    (6, "western digital"),
    (6, "crucial"),
    (7, "corsair"),
    (7, "nzxt"),
    (7, "thermaltake"),
    (7, "cooler master"),
    (8, "corsair"),
    (8, "nzxt"),
    (8, "be quiet!"),
    (8, "noctua"),
]

components = [
    (
        "Intel Core i7-9700K",
        1,
        1,
    ),
    (
        "AMD Ryzen 5 5600X",
        1,
        2,
    ),
    (
        "ASUS ROG Strix B450-F Gaming",
        2,
        3,
    ),
    (
        "MSI B550-A PRO",
        2,
        4,
    ),
    (
        "Gigabyte GeForce RTX 3060",
        3,
        9,
    ),
    (
        "ASUS TUF Gaming GeForce GTX 1660 Super",
        3,
        3,
    ),
    (
        "Corsair Vengeance RGB Pro",
        4,
        12,
    ),
    (
        "G.Skill Ripjaws V Series",
        4,
        13,
    ),
    (
        "Corsair RM750",
        5,
        17,
    ),
    (
        "EVGA SuperNOVA 650 GA",
        5,
        18,
    ),
    (
        "Samsung 970 EVO Plus",
        6,
        21,
    ),
    (
        "Seagate Barracuda 2TB",
        6,
        22,
    ),
    (
        "NZXT H510",
        7,
        27,
    ),
    (
        "Corsair 4000D Airflow",
        7,
        25,
    ),
    (
        "Noctua NH-D15",
        8,
        32,
    ),
    (
        "Cooler Master Hyper 212 RGB",
        8,
        35,
    ),
]

if __name__ == "__main__":
    category = category(categories[0])
    category.create()
    print(category)
