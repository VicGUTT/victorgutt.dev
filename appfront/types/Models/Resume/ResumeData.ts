type Me = {
    readonly name: string;
    readonly title: string;
    readonly phone: string;
    readonly email: string;
    readonly website: string;
    readonly location: {
        readonly city: string;
        readonly postcode: string;
        readonly country: string;
    };
    readonly text: string;
};

type Experience = {
    readonly role: string;
    readonly company: string;
    readonly dates: {
        readonly start: string;
        readonly end: string;
    };
    readonly text: string;
};

type Education = {
    readonly name: string;
    readonly place: string;
    readonly dates: {
        readonly start?: string | null;
        readonly end: string;
    };
    readonly text: string;
};

type Language = {
    readonly name: string;
    readonly level: string;
};

type Tech = {
    readonly title: string;
    readonly items: string[];
};

export type ResumeData = {
    readonly locale: string;
    readonly me: Me;
    readonly experiences: Experience[];
    readonly educations: Education[];
    readonly languages: Language[];
    readonly techs: Tech[];
};
