<?php
namespace app\models;

/**
 * This is the model class for table "estados".
 *
 * @property int $id
 * @property string $codigo_ibge
 * @property string $sigla
 * @property string $nome
 * 
 * @property Cidade[] $cidades;
 */
class Estado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estado';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_ibge', 'sigla', 'nome'], 'required'],
            [['codigo_ibge'], 'string', 'max' => 4],
            [['sigla'], 'string', 'max' => 2],
            [['nome'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cód. Estado',
            'codigo_ibge' => 'Código IBGE',
            'sigla' => 'Sigla',
            'nome' => 'Nome',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCidades()
    {
        return $this->hasMany(Cidade::className(), ['uf' => 'sigla']);
    }
    
    /**
     * Busca uma cidade dentro do estado pelo nome
     */
    public function findCidade($cidade = '') 
    {
        return Cidade::findOne([
            'uf' => $this->sigla,
            'nome' => $cidade,
        ]);
    }
}
